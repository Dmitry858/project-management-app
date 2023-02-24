<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('titles.dashboard'), route('dashboard'));
});

Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.profile'), route('profile'));
});

Breadcrumbs::for('projects', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.projects_index'), route('projects.index'));
});

Breadcrumbs::for('single-project', function (BreadcrumbTrail $trail, $project) {
    $trail->parent('projects');
    $trail->push(__('titles.projects_single', ['name' => $project->name]), route('projects.show', ['project' => $project->id]));
});

Breadcrumbs::for('create-project', function (BreadcrumbTrail $trail) {
    $trail->parent('projects');
    $trail->push(__('titles.projects_create'), route('projects.create'));
});

Breadcrumbs::for('edit-project', function (BreadcrumbTrail $trail, $project) {
    $trail->parent('projects');
    $trail->push(__('titles.projects_edit', ['name' => $project->name]), route('projects.edit', ['project' => $project->id]));
});

Breadcrumbs::for('tasks', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.tasks_index'), route('tasks.index'));
});

Breadcrumbs::for('single-task', function (BreadcrumbTrail $trail, $task) {
    $trail->parent('tasks');
    $trail->push(__('titles.tasks_single', ['name' => $task->name]), route('tasks.show', ['task' => $task->id]));
});

Breadcrumbs::for('create-task', function (BreadcrumbTrail $trail) {
    $trail->parent('tasks');
    $trail->push(__('titles.tasks_create'), route('tasks.create'));
});

Breadcrumbs::for('edit-task', function (BreadcrumbTrail $trail, $task) {
    $trail->parent('tasks');
    $trail->push(__('titles.tasks_edit', ['name' => $task->name]), route('tasks.edit', ['task' => $task->id]));
});

Breadcrumbs::for('members', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.members_index'), route('members.index'));
});

Breadcrumbs::for('create-member', function (BreadcrumbTrail $trail) {
    $trail->parent('members');
    $trail->push(__('titles.members_create'), route('members.create'));
});

Breadcrumbs::for('edit-member', function (BreadcrumbTrail $trail, $member) {
    $trail->parent('members');
    $trail->push(__('titles.members_edit', ['name' => $member->getFullName()]), route('members.edit', ['member' => $member->id]));
});

Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.users_index'), route('users.index'));
});

Breadcrumbs::for('create-user', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(__('titles.users_create'), route('users.create'));
});

Breadcrumbs::for('edit-user', function (BreadcrumbTrail $trail, $user) {
    $userName = $user->name;
    if ($user->last_name) $userName .= ' '.$user->last_name;
    $trail->parent('users');
    $trail->push(__('titles.users_edit', ['name' => $userName]), route('users.edit', ['user' => $user->id]));
});

Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.settings'), route('settings'));
});

Breadcrumbs::for('general-settings', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('titles.general_settings'), route('settings.general'));
});

Breadcrumbs::for('caching', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('titles.caching'), route('settings.caching'));
});

Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('titles.roles_index'), route('roles.index'));
});

Breadcrumbs::for('single-role', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('roles');
    $trail->push(__('titles.roles_single', ['name' => $role->name]), route('roles.show', ['role' => $role->id]));
});

Breadcrumbs::for('create-role', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(__('titles.roles_create'), route('roles.create'));
});

Breadcrumbs::for('edit-role', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('roles');
    $trail->push(__('titles.roles_edit', ['name' => $role->name]), route('roles.edit', ['role' => $role->id]));
});

Breadcrumbs::for('stages', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('titles.stages_index'), route('stages.index'));
});

Breadcrumbs::for('create-stage', function (BreadcrumbTrail $trail) {
    $trail->parent('stages');
    $trail->push(__('titles.stages_create'), route('stages.create'));
});

Breadcrumbs::for('edit-stage', function (BreadcrumbTrail $trail, $stage) {
    $trail->parent('stages');
    $trail->push(__('titles.stages_edit', ['name' => $stage->name]), route('stages.edit', ['stage' => $stage->id]));
});

Breadcrumbs::for('invitations', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('titles.invitations_index'), route('invitations.index'));
});

Breadcrumbs::for('create-invitation', function (BreadcrumbTrail $trail) {
    $trail->parent('invitations');
    $trail->push(__('titles.invitations_create'), route('invitations.create'));
});
