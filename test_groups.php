<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\UserGroups;

// Simulate a user ID that might have groups (e.g., 1)
$userId = 1;

try {
    $userGroupsModel = new UserGroups();
    // Assuming there's a user 1 and some groups.
    // We might need to seed if empty, but let's just run it to see if SQL errors.
    $groups = $userGroupsModel->getGroupsWithDetails($userId);

    echo "Groups for user $userId:\n";
    foreach ($groups as $group) {
        echo "- ID: {$group->id}, Name: {$group->name}, Members: {$group->member_count}\n";
    }
    echo "SQL execution successful.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
