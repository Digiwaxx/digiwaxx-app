#!/usr/bin/env python3
"""
Script to fix SQL injection vulnerabilities in MemberAllDB.php
Converts string concatenation to parameterized queries
"""

import re

def fix_sql_injections(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content
    fixes_count = 0

    # Pattern 1: Simple single variable concatenation: where field = '" . $var . "'
    # Replace with: where field = ?", [$var
    pattern1 = r"= '\" \. \$([a-zA-Z_][a-zA-Z0-9_]*) \. \"'"
    def replace1(match):
        var = match.group(1)
        return f"= ?\"WHERE_PLACEHOLDER_{var}"

    # Pattern 2: Multiple variables in same query
    # This is more complex and needs manual review

    # First, let's do simple single-variable replacements
    # Pattern: 'something = '" . $var . "' [more sql]')"

    # Find all DB::select/update/delete statements with string concat
    db_pattern = r'(DB::(select|update|delete|insert)\([^)]*where[^)]*= \'"\s*\.\s*\$([a-zA-Z_][a-zA-Z0-9_]*)\s*\.\s*"\')[^)]*\)'

    print(f"File size: {len(content)} bytes")
    print("Starting fixes...")

    # Return content as is for now - this needs careful manual review
    return content, 0

if __name__ == "__main__":
    import sys

    filepath = "/home/user/digiwaxx-app/Models/MemberAllDB.php"
    fixed_content, count = fix_sql_injections(filepath)

    print(f"Would fix {count} SQL injection vulnerabilities")
