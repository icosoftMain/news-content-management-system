<table class="table" id="tbl-admin-users">
    <thead>
    <tr>
        <th>Full Name</th>
        <th class="center-child">Gender</th>
        <th>User Name</th>
        <th class="center-child">Phone Number</th>
        <th class="center-child">Email</th>
        <th class="center-child">Role</th>
        <th class="center-child">Suspend Account</th>
    </tr>
    </thead>
    <tbody> 
        @each $allUsers as $key => $user:
            @if $key === 'pagLim' || (is_numeric($key) && $user['username'] === '@ai_admin_lapi'): @thenskip @endif
            <tr class="centerit">
                <td>{# $user['lastName'].', '.$user['firstName'] #}</td>
                <td class="center-child">{# ['M' => 'Male','F' => 'Female' ][$user['gender']] #}</td>
                <td>{# $user['username'] #}</td>
                <td class="center-child">{# $user['phoneNumber'] #}</td>
                <td class="center-child">{# $user['email'] #}</td>
                <td class="center-child">
                    {#
                        [
                            'A' => 'Administrator',
                            'U' => 'User',
                            'E' => 'Editor',
                            'M' => 'Moderator'
                        ][$user['accessLevel']]
                    #}
                </td>

                <td class="center-child">
                    <a href="#!" class="delete_user" title="Quarantine this account" data-toggle="modal" data-target="#deleteAlertModal"><i class="fa fa-trash"></i></a>
                </td>
                <input class="user_name" type="hidden" value="{# $user['lastName'].', '.$user['firstName'] #}"/>
                <input class="user_token" type="hidden" value="{# $user['memberId'] #}"/>
            </tr>
        @endeach
    </tbody>
</table>