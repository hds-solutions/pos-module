<?php

namespace hDSSolutions\Laravel\Models\Policies;

use hDSSolutions\Laravel\Models\POS as Resource;
use HDSSolutions\Laravel\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class POSPolicy {
    use HandlesAuthorization;

    public function viewAny(User $user) {
        return $user->can('pos.crud.index');
    }

    public function view(User $user, Resource $resource) {
        return $user->can('pos.crud.show');
    }

    public function create(User $user) {
        return $user->can('pos.crud.create');
    }

    public function update(User $user, Resource $resource) {
        return $user->can('pos.crud.update');
    }

    public function delete(User $user, Resource $resource) {
        return $user->can('pos.crud.destroy');
    }

    public function restore(User $user, Resource $resource) {
        return $user->can('pos.crud.destroy');
    }

    public function forceDelete(User $user, Resource $resource) {
        return $user->can('pos.crud.destroy');
    }
}
