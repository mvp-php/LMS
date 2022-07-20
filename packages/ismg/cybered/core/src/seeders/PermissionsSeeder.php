<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use CyberEd\Core\Models\Permission;
use Carbon\Carbon;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aPermissions =  [
            // Course permissions
            [
                'title'         => 'Create',
                'description'   => 'Can create course',
                'table_name'    => 'courses',
                'action_name'   => 'create_course',
                'module_name'   => 'Course',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Edit',
                'description'   => 'Can edit course',
                'table_name'    => 'courses',
                'action_name'   => 'edit_course',
                'module_name'   => 'Course',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Review',
                'description'   => 'Can review course, created by admins or instructors',
                'table_name'    => 'courses',
                'action_name'   => 'review_course',
                'module_name'   => 'Course',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Hide',
                'description'   => 'Can hide course, created by admins or instructors',
                'table_name'    => 'courses',
                'action_name'   => 'hide_course',
                'module_name'   => 'Course',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Delete',
                'description'   => 'Can delete course, created by admins or instructors',
                'table_name'    => 'courses',
                'action_name'   => 'delete_course',
                'module_name'   => 'Course',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            // Learning path permissions
            [
                'title'         => 'Create',
                'description'   => 'Can create Learning path',
                'table_name'    => 'learning_paths',
                'action_name'   => 'create_learning_path',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Edit',
                'description'   => 'Can edit Learning path',
                'table_name'    => 'learning_paths',
                'action_name'   => 'edit_learning_path',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Review',
                'description'   => 'Can review Learning path, created by admins or instructors',
                'table_name'    => 'learning_paths',
                'action_name'   => 'review_learning_path',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Publish',
                'description'   => 'Can publish Learning path, created by admins or instructors',
                'table_name'    => 'learning_paths',
                'action_name'   => 'publish_learning_path',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Hide',
                'description'   => 'Can hide Learning path, created by admins or instructors',
                'table_name'    => 'learning_paths',
                'action_name'   => 'hide_learning_path',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Delete',
                'description'   => 'Can delete Learning path, created by admins or instructors',
                'table_name'    => 'learning_paths',
                'action_name'   => 'review_learning_path',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            // Group permissions
            [
                'title'         => 'Create',
                'description'   => 'Can create Groups',
                'table_name'    => 'groups',
                'action_name'   => 'create_group',
                'module_name'   => 'Group',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Edit',
                'description'   => 'Can edit Groups',
                'table_name'    => 'groups',
                'action_name'   => 'edit_group',
                'module_name'   => 'Group',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Approve / Reject',
                'description'   => 'Can review Group create request by instructors',
                'table_name'    => 'group_requests',
                'action_name'   => 'review_group_request',
                'module_name'   => 'Group',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Add Member',
                'description'   => 'Can add member in groups',
                'table_name'    => 'group_user',
                'action_name'   => 'add_group_user',
                'module_name'   => 'Group',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            [
                'title'         => 'Remove Member',
                'description'   => 'Can delete members from groups',
                'table_name'    => 'group_user',
                'action_name'   => 'remove_group_user',
                'module_name'   => 'Group',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ],
            // Instructor permissions
            [
                'title'         => 'Yes',
                'description'   => 'Can take actions on instructor requests',
                'table_name'    => 'instructor_requests',
                'action_name'   => 'can_approve_instructor_request',
                'module_name'   => 'LearningPath',
                'activated'     => 1,
                'deleted'       => 0,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
                'deleted_at'    => NULL
            ]
        ];
        Permission::insert($aPermissions);
    }
}
