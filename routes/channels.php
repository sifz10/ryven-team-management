<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for user notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Public channel for task comments (real-time updates)
Broadcast::channel('task.{taskId}', function ($user, $taskId) {
    return true; // Anyone viewing the task can see comments
});
