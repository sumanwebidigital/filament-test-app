<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // if($user->isAdmin() || $user->isEditor() || $user->isUser()){
        //     if($user->hasPermissionTo('View Post')){
        //         return true;
        //     }  
        // }
        // return false;

        return $user->isAdmin() || $user->isEditor() || $user->isUser();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        if($user->isAdmin() || $user->isEditor() || $user->isUser()){
            if($user->hasPermissionTo('View Post')){
                return true;
            }  
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // if($user->isAdmin() || $user->isEditor() || $user->isUser()){
        //     if($user->hasPermissionTo('Create Post')){
        //         return true;
        //     }  
        // }
        // return false;
        return $user->isAdmin() || $user->isEditor() || $user->isUser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // if($user->isAdmin() || $user->isEditor() || $user->isUser()){
        //     if($user->hasPermissionTo('Edit Post')){

        //         // $post = Post::with('authors')->find($post->id);
        //         // dd($post);

        //         return true;
        //     }  
        // }
        // return false;
        return $user->isAdmin() || $user->isEditor() || $user->isUser();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if($user->isAdmin() || $user->isEditor() || $user->isUser()){
            if($user->hasPermissionTo('Delete Post')){
                return true;
            }  
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }
}
