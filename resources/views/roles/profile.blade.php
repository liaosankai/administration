<div class="panel panel-primary">

    <div class="panel-heading">

        <i class="fa fa-list"></i>
        Profile <span class="hidden-xs">Details</span>

        <div class="btn-group pull-right">

            <a href="{{ route('admin.roles.edit', [$role->getKey()]) }}" class="btn btn-xs btn-warning">
                <i class="fa fa-edit"></i>
                Edit
            </a>

            {{-- Prevent user from deleting role that they are apart of. --}}
            @if (!request()->user()->hasRole($role->name))

                <a
                        data-post="DELETE"
                        data-title="Delete Role?"
                        data-message="Are you sure you want to delete this role?"
                        href="{{ route('admin.roles.destroy', [$role->getKey()]) }}"
                        class="btn btn-xs btn-danger"
                >
                    <i class="fa fa-trash"></i>
                    Delete
                </a>

            @endif

        </div>
    </div>

    <div class="panel-body">

        <table class="table table-striped">

            <tbody>

            <tr>
                <th>Label</th>
                <td>{{ $role->label }}</td>
            </tr>

            <tr>
                <th>Name</th>
                <td>{{ $role->name }}</td>
            </tr>

            </tbody>

        </table>

    </div>

</div>
