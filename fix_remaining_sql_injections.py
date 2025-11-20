#!/usr/bin/env python3
"""
SQL Injection Vulnerability Fixer for Admin.php
Fixes all remaining SQL injection vulnerabilities by converting string concatenation to parameterized queries.
"""

import re
import sys

def fix_sql_injections(file_path):
    """Fix SQL injection vulnerabilities in the given file."""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content
    fixes_count = 0

    # Pattern 1: Fix member_subscriptions queries with limit
    pattern1 = r'DB::select\("select \*  from  member_subscriptions\s+where member_Id = \'" \. \$memberId \. "\' and status = \'1\' order by subscription_Id desc limit \$start, \$limit"\)'
    replacement1 = 'DB::select("select *  from  member_subscriptions	where member_Id = ? and status = \'1\' order by subscription_Id desc limit ?, ?", [$memberId, $start, $limit])'
    content, n = re.subn(pattern1, replacement1, content)
    fixes_count += n

    # Pattern 2: Fix member_subscriptions queries without limit
    pattern2 = r'DB::select\("select \*  from  member_subscriptions\s+where member_Id = \'" \. \$memberId \. "\' and status = \'1\'"\)'
    replacement2 = 'DB::select("select *  from  member_subscriptions	where member_Id = ? and status = \'1\'", [$memberId])'
    content, n = re.subn(pattern2, replacement2, content)
    fixes_count += n

    # Pattern 3: Fix delete from members
    pattern3 = r'DB::select\("delete from members where id = \'" \. \$memberId \. "\'"\)'
    replacement3 = 'DB::delete("delete from members where id = ?", [$memberId])'
    content, n = re.subn(pattern3, replacement3, content)
    fixes_count += n

    # Pattern 4: Fix checkDuplicateMemberEmail
    pattern4 = r'DB::select\("select id from members where email= \'" \. urlencode\(trim\(\$email\)\) \. "\'"\)'
    replacement4 = 'DB::select("select id from members where email= ?", [urlencode(trim($email))])'
    content, n = re.subn(pattern4, replacement4, content)
    fixes_count += n

    # Pattern 5: Fix simple INSERT queries for members_mass_media
    pattern5 = r'DB::select\("insert into `members_mass_media` \(`member`, `mediatype_tvfilm`, `mediatype_publication`, `mediatype_newmedia`, `mediatype_newsletter`, `media_name`, `media_website`, `media_department`\) values \(\'" \. \$insertId \. "\', \'" \. \$massTv \. "\', \'" \. \$massPublication \. "\', \'" \. \$massDotcom \. "\', \'" \. \$massNewsletter \. "\', \'" \. \$massName \. "\', \'" \. \$massWebsite \. "\', \'" \. \$massDepartment \. "\'\)"\)'
    replacement5 = '''$massMediaData = array(
                'member' => $insertId,
                'mediatype_tvfilm' => $massTv,
                'mediatype_publication' => $massPublication,
                'mediatype_newmedia' => $massDotcom,
                'mediatype_newsletter' => $massNewsletter,
                'media_name' => $massName,
                'media_website' => $massWebsite,
                'media_department' => $massDepartment
            );
            DB::table('members_mass_media')->insert($massMediaData)'''
    content, n = re.subn(pattern5, replacement5, content)
    fixes_count += n

    # Pattern 6: Fix simple INSERT queries for members_record_label
    pattern6 = r'DB::select\("insert into `members_record_label` \(`member`, `labeltype_major`, `labeltype_indy`, `labeltype_distribution`, `label_name`, `label_department`\) values \(\'" \. \$insertId \. "\', \'" \. \$recordMajor \. "\', \'" \. \$recordIndy \. "\', \'" \. \$recordDistribution \. "\', \'" \. \$recordName \. "\', \'" \. \$recordDepartment \. "\'\)"\)'
    replacement6 = '''$recordLabelData = array(
                'member' => $insertId,
                'labeltype_major' => $recordMajor,
                'labeltype_indy' => $recordIndy,
                'labeltype_distribution' => $recordDistribution,
                'label_name' => $recordName,
                'label_department' => $recordDepartment
            );
            DB::table('members_record_label')->insert($recordLabelData)'''
    content, n = re.subn(pattern6, replacement6, content)
    fixes_count += n

    # Pattern 7: Fix simple INSERT queries for members_management
    pattern7 = r'DB::select\("insert into `members_management` \(`member`, `managementtype_artist`, `managementtype_tour`, `managementtype_personal`, `managementtype_finance`, `management_name`, `management_who`, `management_industry`\) values \(\'" \. \$insertId \. "\', \'" \. \$managementArtist \. "\', \'" \. \$managementTour \. "\', \'" \. \$managementPersonal \. "\', \'" \. \$managementFinance \. "\', \'" \. \$managementName \. "\', \'" \. \$managementWho \. "\', \'" \. \$managementIndustry \. "\'\)"\)'
    replacement7 = '''$managementData = array(
                'member' => $insertId,
                'managementtype_artist' => $managementArtist,
                'managementtype_tour' => $managementTour,
                'managementtype_personal' => $managementPersonal,
                'managementtype_finance' => $managementFinance,
                'management_name' => $managementName,
                'management_who' => $managementWho,
                'management_industry' => $managementIndustry
            );
            DB::table('members_management')->insert($managementData)'''
    content, n = re.subn(pattern7, replacement7, content)
    fixes_count += n

    # Pattern 8: Fix simple INSERT queries for members_clothing_apparel
    pattern8 = r'DB::select\("insert into `members_clothing_apparel` \(`member`, `clothing_name`, `clothing_department`\) values \(\'" \. \$insertId \. "\', \'" \. \$clothingName \. "\', \'" \. \$clothingDepartment \. "\'\)"\)'
    replacement8 = '''$clothingData = array(
                'member' => $insertId,
                'clothing_name' => $clothingName,
                'clothing_department' => $clothingDepartment
            );
            DB::table('members_clothing_apparel')->insert($clothingData)'''
    content, n = re.subn(pattern8, replacement8, content)
    fixes_count += n

    # Pattern 9: Fix simple INSERT queries for members_promoter
    pattern9 = r'DB::select\("insert into `members_promoter` \(`member`, `promotertype_indy`, `promotertype_club`, `promotertype_event`, `promotertype_street`, `promoter_name`, `promoter_department`, `promoter_website`\) values \(\'" \. \$insertId \. "\', \'" \. \$promoterIndy \. "\', \'" \. \$promoterClub \. "\', \'" \. \$promoterSpecial \. "\', \'" \. \$promoterStreet \. "\', \'" \. \$promoterName \. "\', \'" \. \$promoterDepartment \. "\', \'" \. \$promoterWebsite \. "\'\)"\)'
    replacement9 = '''$promoterData = array(
                'member' => $insertId,
                'promotertype_indy' => $promoterIndy,
                'promotertype_club' => $promoterClub,
                'promotertype_event' => $promoterSpecial,
                'promotertype_street' => $promoterStreet,
                'promoter_name' => $promoterName,
                'promoter_department' => $promoterDepartment,
                'promoter_website' => $promoterWebsite
            );
            DB::table('members_promoter')->insert($promoterData)'''
    content, n = re.subn(pattern9, replacement9, content)
    fixes_count += n

    # Pattern 10: Fix simple INSERT queries for members_special_services
    pattern10 = r'DB::select\("insert into `members_special_services` \(`member`, `servicestype_corporate`, `servicestype_graphicdesign`, `servicestype_webdesign`, `servicestype_other`, `services_name`, `services_website`\) values \(\'" \. \$insertId \. "\', \'" \. \$specialCorporate \. "\', \'" \. \$specialGraphic \. "\', \'" \. \$specialWeb \. "\', \'" \. \$specialOther \. "\', \'" \. \$specialName \. "\', \'" \. \$specialWebsite \. "\'\)"\)'
    replacement10 = '''$specialServicesData = array(
                'member' => $insertId,
                'servicestype_corporate' => $specialCorporate,
                'servicestype_graphicdesign' => $specialGraphic,
                'servicestype_webdesign' => $specialWeb,
                'servicestype_other' => $specialOther,
                'services_name' => $specialName,
                'services_website' => $specialWebsite
            );
            DB::table('members_special_services')->insert($specialServicesData)'''
    content, n = re.subn(pattern10, replacement10, content)
    fixes_count += n

    # Pattern 11: Fix simple INSERT queries for members_production_talent
    pattern11 = r'DB::select\("insert into `members_production_talent` \(`member`, `productiontype_artist`, `productiontype_producer`, `productiontype_choreographer`, `productiontype_sound`, `production_name`\) values \(\'" \. \$insertId \. "\', \'" \. \$productionArtist \. "\', \'" \. \$productionProducer \. "\', \'" \. \$productionChoregrapher \. "\', \'" \. \$productionSound \. "\', \'" \. \$productionName \. "\'\)"\)'
    replacement11 = '''$productionData = array(
                'member' => $insertId,
                'productiontype_artist' => $productionArtist,
                'productiontype_producer' => $productionProducer,
                'productiontype_choreographer' => $productionChoregrapher,
                'productiontype_sound' => $productionSound,
                'production_name' => $productionName
            );
            DB::table('members_production_talent')->insert($productionData)'''
    content, n = re.subn(pattern11, replacement11, content)
    fixes_count += n

    # Only write if changes were made
    if content != original_content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Successfully fixed {fixes_count} SQL injection vulnerabilities!")
        return fixes_count
    else:
        print("No changes made - patterns may have already been fixed or not found.")
        return 0

if __name__ == "__main__":
    file_path = "/home/user/digiwaxx-app/Models/Admin.php"
    fixes = fix_sql_injections(file_path)
    sys.exit(0 if fixes >= 0 else 1)
