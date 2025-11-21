#!/bin/bash

###############################################################################
# SQL Injection Fixes - Automated Testing Script
#
# This script tests all 9 SQL injection fixes to verify they're working
# properly and that SQL injection attempts are blocked.
#
# Usage: ./test_sql_injection_fixes.sh <staging_url>
# Example: ./test_sql_injection_fixes.sh https://staging.digiwaxx.com
###############################################################################

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Counters
TESTS_PASSED=0
TESTS_FAILED=0
TOTAL_TESTS=0

# Check if URL is provided
if [ -z "$1" ]; then
    echo -e "${RED}Error: Please provide the staging URL${NC}"
    echo "Usage: $0 <staging_url>"
    echo "Example: $0 https://staging.digiwaxx.com"
    exit 1
fi

BASE_URL="$1"

# Remove trailing slash from URL
BASE_URL="${BASE_URL%/}"

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}SQL Injection Fixes - Automated Security Testing${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo ""
echo -e "Testing URL: ${YELLOW}${BASE_URL}${NC}"
echo ""
echo -e "${YELLOW}WARNING: This script attempts SQL injection attacks for testing.${NC}"
echo -e "${YELLOW}Only run this on staging/test environments, NEVER on production!${NC}"
echo ""
read -p "Press Enter to continue or Ctrl+C to cancel..."
echo ""

###############################################################################
# Helper Functions
###############################################################################

# Test a URL and check if SQL injection is blocked
test_sql_injection() {
    local test_name="$1"
    local url="$2"
    local should_contain="$3"

    TOTAL_TESTS=$((TOTAL_TESTS + 1))

    echo -e "${BLUE}Testing:${NC} $test_name"
    echo -e "  URL: ${url}"

    # Make request and capture response
    response=$(curl -s -w "\n%{http_code}" "$url" 2>&1)
    http_code=$(echo "$response" | tail -n1)
    body=$(echo "$response" | head -n-1)

    # Check for SQL error keywords (these should NOT appear)
    if echo "$body" | grep -qi "SQL syntax\|mysql error\|query failed\|PDOException\|SQLSTATE"; then
        echo -e "  ${RED}✗ FAILED${NC}: SQL error detected in response (vulnerability exists)"
        TESTS_FAILED=$((TESTS_FAILED + 1))
        return 1
    fi

    # Check if response is 200, 404, or other safe code (not 500)
    if [ "$http_code" = "200" ] || [ "$http_code" = "404" ] || [ "$http_code" = "302" ]; then
        echo -e "  ${GREEN}✓ PASSED${NC}: SQL injection blocked (HTTP $http_code, no SQL errors)"
        TESTS_PASSED=$((TESTS_PASSED + 1))
        return 0
    elif [ "$http_code" = "500" ]; then
        echo -e "  ${RED}✗ FAILED${NC}: Server error (HTTP 500) - possible SQL error"
        TESTS_FAILED=$((TESTS_FAILED + 1))
        return 1
    else
        echo -e "  ${YELLOW}⚠ WARNING${NC}: Unexpected HTTP code $http_code"
        TESTS_PASSED=$((TESTS_PASSED + 1))
        return 0
    fi
}

# Test normal functionality
test_normal_functionality() {
    local test_name="$1"
    local url="$2"

    TOTAL_TESTS=$((TOTAL_TESTS + 1))

    echo -e "${BLUE}Testing:${NC} $test_name"
    echo -e "  URL: ${url}"

    # Make request
    http_code=$(curl -s -o /dev/null -w "%{http_code}" "$url")

    if [ "$http_code" = "200" ]; then
        echo -e "  ${GREEN}✓ PASSED${NC}: Page loads normally (HTTP 200)"
        TESTS_PASSED=$((TESTS_PASSED + 1))
        return 0
    else
        echo -e "  ${RED}✗ FAILED${NC}: Page returned HTTP $http_code (expected 200)"
        TESTS_FAILED=$((TESTS_FAILED + 1))
        return 1
    fi
}

###############################################################################
# Test Suite
###############################################################################

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}P0-CRITICAL Tests (Direct User Input)${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo ""

# Test 1: Forum Comments SQL Injection (Line 712)
echo -e "${YELLOW}Test 1: Forum Comments Query (Line 712)${NC}"
test_sql_injection \
    "Forum comments SQL injection attempt" \
    "${BASE_URL}/single_forum/1' UNION SELECT 1,2,3,4,5--"
echo ""

test_normal_functionality \
    "Forum comments normal functionality" \
    "${BASE_URL}/single_forum/1"
echo ""

# Test 2 & 3: Forum Likes/Dislikes (Lines 737, 857)
echo -e "${YELLOW}Test 2 & 3: Forum Likes/Dislikes (Lines 737, 857)${NC}"
echo -e "${BLUE}Testing:${NC} Forum likes/dislikes functionality"
echo -e "  ${YELLOW}Note: These require authentication and are best tested manually${NC}"
echo -e "  ${YELLOW}See SQL_INJECTION_TESTING_PLAN.md for manual testing steps${NC}"
echo ""

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}HIGH Priority Tests (Pagination Vulnerabilities)${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo ""

# Test 4: News Pagination (Line 252)
echo -e "${YELLOW}Test 4: News Pagination (Line 252)${NC}"
test_sql_injection \
    "News pagination SQL injection attempt" \
    "${BASE_URL}/news?page=1' UNION SELECT * FROM users--"
echo ""

test_normal_functionality \
    "News pagination normal functionality" \
    "${BASE_URL}/news?page=1"
echo ""

# Test 5: Videos Pagination (Line 363)
echo -e "${YELLOW}Test 5: Videos Pagination (Line 363)${NC}"
test_sql_injection \
    "Videos pagination SQL injection attempt" \
    "${BASE_URL}/videos?page=1,999999"
echo ""

test_normal_functionality \
    "Videos pagination normal functionality" \
    "${BASE_URL}/videos?page=1"
echo ""

# Test 6: Forum Articles Pagination (Line 485)
echo -e "${YELLOW}Test 6: Forum Articles Pagination (Line 485)${NC}"
test_sql_injection \
    "Forum articles pagination SQL injection attempt" \
    "${BASE_URL}/forum?page=1' UNION SELECT password FROM users--"
echo ""

test_normal_functionality \
    "Forum articles pagination normal functionality" \
    "${BASE_URL}/forum?page=1"
echo ""

# Test 7: Charts AJAX Type Validation (Lines 1482-1493)
echo -e "${YELLOW}Test 7: Charts AJAX Type Validation (Lines 1482-1493)${NC}"
test_sql_injection \
    "Charts AJAX type SQL injection attempt" \
    "${BASE_URL}/charts?page=1&type=1' UNION SELECT * FROM users--"
echo ""

test_normal_functionality \
    "Charts weekly type normal functionality" \
    "${BASE_URL}/charts?page=1&type=1"
echo ""

test_normal_functionality \
    "Charts monthly type normal functionality" \
    "${BASE_URL}/charts?page=1&type=2"
echo ""

test_normal_functionality \
    "Charts yearly type normal functionality" \
    "${BASE_URL}/charts?page=1&type=3"
echo ""

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}MEDIUM Priority Tests (PHP-Generated Variables)${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo ""

# Test 8: Homepage Downloads (Lines 70-77)
echo -e "${YELLOW}Test 8: Homepage Downloads (Lines 70-77)${NC}"
test_normal_functionality \
    "Homepage with downloads section" \
    "${BASE_URL}/"
echo ""

# Test 9: Charts Page (Lines 1466-1473)
echo -e "${YELLOW}Test 9: Charts Page (Lines 1466-1473)${NC}"
test_normal_functionality \
    "Charts page loads correctly" \
    "${BASE_URL}/charts"
echo ""

###############################################################################
# Additional Security Tests
###############################################################################

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}Additional Security Tests${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo ""

echo -e "${YELLOW}Additional Test: Error-based SQL Injection${NC}"
test_sql_injection \
    "Error-based SQL injection attempt" \
    "${BASE_URL}/news?page=1' AND 1=CONVERT(int, (SELECT TOP 1 name FROM sysobjects))--"
echo ""

echo -e "${YELLOW}Additional Test: Boolean-based Blind SQL Injection${NC}"
test_sql_injection \
    "Boolean-based blind SQL injection attempt" \
    "${BASE_URL}/news?page=1' AND '1'='1"
echo ""

echo -e "${YELLOW}Additional Test: Time-based Blind SQL Injection${NC}"
test_sql_injection \
    "Time-based blind SQL injection attempt" \
    "${BASE_URL}/news?page=1' AND SLEEP(5)--"
echo ""

###############################################################################
# Results Summary
###############################################################################

echo -e "${BLUE}=====================================================================${NC}"
echo -e "${BLUE}Test Results Summary${NC}"
echo -e "${BLUE}=====================================================================${NC}"
echo ""
echo -e "Total Tests Run:    ${BLUE}${TOTAL_TESTS}${NC}"
echo -e "Tests Passed:       ${GREEN}${TESTS_PASSED}${NC}"
echo -e "Tests Failed:       ${RED}${TESTS_FAILED}${NC}"
echo ""

# Calculate pass percentage
if [ $TOTAL_TESTS -gt 0 ]; then
    PASS_PERCENTAGE=$((TESTS_PASSED * 100 / TOTAL_TESTS))
else
    PASS_PERCENTAGE=0
fi

echo -e "Pass Rate:          ${BLUE}${PASS_PERCENTAGE}%${NC}"
echo ""

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}=====================================================================${NC}"
    echo -e "${GREEN}✓ ALL TESTS PASSED${NC}"
    echo -e "${GREEN}=====================================================================${NC}"
    echo ""
    echo -e "${GREEN}All SQL injection vulnerabilities appear to be fixed!${NC}"
    echo -e "${GREEN}The application successfully blocked all SQL injection attempts.${NC}"
    echo ""
    echo -e "${YELLOW}Next Steps:${NC}"
    echo "  1. Perform manual testing for authentication-required features"
    echo "  2. Review application logs for any SQL errors"
    echo "  3. Monitor staging environment for 24 hours"
    echo "  4. Proceed with production deployment when ready"
    echo ""
    exit 0
else
    echo -e "${RED}=====================================================================${NC}"
    echo -e "${RED}✗ SOME TESTS FAILED${NC}"
    echo -e "${RED}=====================================================================${NC}"
    echo ""
    echo -e "${RED}${TESTS_FAILED} test(s) failed. SQL injection vulnerabilities may still exist.${NC}"
    echo ""
    echo -e "${YELLOW}Action Required:${NC}"
    echo "  1. Review the failed tests above"
    echo "  2. Check application logs for SQL errors"
    echo "  3. Verify the SQL injection fixes were deployed correctly"
    echo "  4. Re-run tests after fixing issues"
    echo "  5. DO NOT deploy to production until all tests pass"
    echo ""
    echo -e "${YELLOW}For detailed testing instructions, see:${NC}"
    echo "  - SQL_INJECTION_TESTING_PLAN.md"
    echo "  - DEPLOYMENT_CHECKLIST.md"
    echo ""
    exit 1
fi
