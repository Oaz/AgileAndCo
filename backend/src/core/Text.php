<?php
/*
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Bga\Games\AgileAndCo;

class Text
{
    public static array $messages = [
        'ACTIVITY_WAS_CHOSEN' => '${player_name} chooses activity "${activity}"',
        'POTENTIAL_ADJUSTMENT' => '${player_name} loses ${potential_loss} potential',
        'ACTIVITY_DEVELOPMENT_TITLE' => 'Development',
        'ACTIVITY_DEVELOPMENT_IMPACT' => '${player_name} develops ${product_count} product(s)',
        'ACTIVITY_DEVELOPMENT_IMPACT_PLUS' => '${player_name} develops ${product_count} product(s) and increases potential by 1',
        'ACTIVITY_DEPLOYMENT_TITLE' => 'Deployment',
        'ACTIVITY_DEPLOYMENT_IMPACT' => '${player_name} deploys ${product_count} product(s) and increases potential by ${earnings}',
        'ACTIVITY_CONFERENCE_TITLE' => 'Conference',
        'ACTIVITY_CONFERENCE_IMPACT' => 'Conference increases ${player_name} potential by ${potential_gain}',
        'ACTIVITY_COACH_TITLE' => 'Coach',
        'ACTIVITY_COACH_IMPACT' => 'Coach increases ${player_name} potential by 1',
        'ACTIVITY_RETROSPECTIVE_TITLE' => 'Retrospective',
        'ACTIVITY_RETROSPECTIVE_IMPACT_MATURITY' => '${player_name} pays ${payment} and gets ${improvement}',
        'ACTIVITY_RETROSPECTIVE_IMPACT_VALUE' => '${player_name} pays ${payment} and adopts ${improvement} value',
        'ACTIVITY_RETROSPECTIVE_IMPACT_TEAM' => '${player_name} pays ${payment} and hires a new ${improvement} team',
        "PRODUCT_TEAM_ADVERGAME" => 'Advertising games',
        "PRODUCT_TEAM_EDUCATION" => 'Educational games',
        "PRODUCT_TEAM_SOCIAL" => 'Social games',
        "PRODUCT_TEAM_MMOG" => 'Massively multiplayer online games',
        "AGILE_MATURITY_PASSIONATE_DEVELOPER_TITLE" => 'Passionate Developer',
        "AGILE_MATURITY_AGILE_PRACTITIONER_TITLE" => 'Agile Practitioner',
        "AGILE_MATURITY_USER_EXPERIENCE_TITLE" => 'User Experience',
        "AGILE_MATURITY_PAIR_PROGRAMMING_TITLE" => 'Pair Programming',
        "AGILE_MATURITY_AGILE_ORGANIZER_TITLE" => 'Agile Organizer',
        "AGILE_MATURITY_FEEDBACK_SESSIONS_TITLE" => 'Feedback Sessions',
        "AGILE_MATURITY_CLEAN_CODE_TITLE" => 'Clean Code',
        "AGILE_MATURITY_CONTINUOUS_DELIVERY_TITLE" => 'Continuous Delivery',
        "AGILE_MATURITY_DEVOPS_TITLE" => 'DevOps',
        "AGILE_MATURITY_AGILE_HR_TITLE" => 'Agile HR',
        "AGILE_MATURITY_ENGAGED_USERS_TITLE" => 'Engaged Users',
        "AGILE_MATURITY_INTERNAL_COACH_TITLE" => 'Internal Coach',
        "AGILE_MATURITY_DETAILED_PLANNING_TITLE" => 'Detailed Planning',
        "AGILE_MATURITY_TEST_TEAM_TITLE" => 'Test Team',
        "AGILE_MATURITY_APPLICATION_FRAMEWORK_TITLE" => 'Application Framework',
        "AGILE_MATURITY_AGILE_CERTIFICATION_TITLE" => 'Agile Certification',
        "AGILE_MATURITY_SOFTWARE_CRAFTSMANSHIP_TITLE" => 'Software Craftsmanship',
        "AGILE_MATURITY_AGILE_SENSEI_TITLE" => 'Agile Sensei',
        "AGILE_MATURITY_PRODUCT_VISION_TITLE" => 'Product Vision',
        "AGILE_VALUE_HUMOR_TITLE" => 'Humor',
        "AGILE_VALUE_FEEDBACK_TITLE" => 'Feedback',
        "AGILE_VALUE_SIMPLICITY_TITLE" => 'Simplicity',
        "AGILE_VALUE_FOCUS_TITLE" => 'Focus',
        "AGILE_VALUE_OPENNESS_TITLE" => 'Openness',
        "AGILE_VALUE_COURAGE_TITLE" => 'Courage',
        "AGILE_VALUE_RESPECT_TITLE" => 'Respect',
    ];

    public static function get(string $messageId): string
    {
        return clienttranslate(self::$messages[$messageId]) ?? $messageId;
    }
}