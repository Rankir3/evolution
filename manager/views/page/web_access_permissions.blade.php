@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script type="text/javascript">
            function deletegroup(groupid, type) {
                if(confirm("{{ $_lang['confirm_delete_group'] }}") === true) {
                    if(type === 'usergroup') {
                        document.location.href = "index.php?a=92&usergroup=" + groupid + "&operation=delete_user_group";
                    }
                    else if(type === 'documentgroup') {
                        document.location.href = "index.php?a=92&documentgroup=" + groupid + "&operation=delete_document_group";
                    }
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                var h1help = document.querySelector('h1 > .help');
                h1help.onclick = function() {
                    document.querySelector('.element-edit-message').classList.toggle('show')
                }
            });
        </script>
    @endpush
    <h1>
        <i class="fa fa-male"></i>{{ $_lang['web_access_permissions'] }}<i class="fa fa-question-circle help"></i>
    </h1>

    <div class="container element-edit-message">
        <div class="alert alert-info">{{ $_lang['access_permissions_introtext'] }}</div>
    </div>

    @if($modx->getConfig('use_udperms') !== true)
        <div class="container">
            <div class="alert alert-danger">{{ $_lang['access_permissions_off'] }}</div>
        </div>
    @endif

    <div class="tab-pane" id="wuapPane">
        <script type="text/javascript">
            tp1 = new WebFXTabPane(document.getElementById("wuapPane"), true);
        </script>

        <div class="tab-page" id="tabPage1">
            <h2 class="tab">{{ $_lang['web_access_permissions_user_groups'] }}</h2>
            <script type="text/javascript">tp1.addTabPage(document.getElementById("tabPage1"));</script>

            <div class="container container-body">
                <p class="element-edit-message-tab alert alert-warning">{{ $_lang['access_permissions_users_tab'] }}</p>
                <div class="form-group">
                    <b>{{ $_lang['access_permissions_add_user_group'] }}</b>
                    <form method="post" action="index.php" name="accesspermissions">
                        <input type="hidden" name="a" value="92" />
                        <input type="hidden" name="operation" value="add_user_group" />
                        <div class="input-group">
                            <input class="form-control" type="text" value="" name="newusergroup" />
                            <div class="input-group-btn">
                                <input class="btn btn-success" type="submit" value="{{ $_lang['submit'] }}" />
                            </div>
                        </div>
                    </form>
                </div>

                @if($userGroups->count() === 0)
                    <div class="text-danger">{{ $_lang['no_groups_found'] }}</div>
                @else
                    <?php /** @var EvolutionCMS\Models\MembergroupName $userGroup */?>
                    @foreach($userGroups as $userGroup)
                        <div class="form-group">
                            <form method="post" action="index.php" name="accesspermissions">
                                <input type="hidden" name="a" value="92" />
                                <input type="hidden" name="groupid" value="{{ $userGroup->getKey() }}" />
                                <input type="hidden" name="operation" value="rename_user_group" />
                                <div class="input-group">
                                    <input class="form-control" type="text" name="newgroupname" value="{{ $userGroup->name }}" />
                                    <div class="input-group-btn">
                                        <input class="btn btn-secondary" type="submit" value="{{ $_lang['rename'] }}" />
                                        <input class="btn btn-danger" type="button" value="{{ $_lang['delete'] }}" onclick="deletegroup({{ $userGroup->getKey() }}, 'usergroup');" />
                                    </div>
                                </div>
                            </form>
                            <b>{{ $_lang['access_permissions_users_in_group'] }}</b>
                            @if($userGroup->users->count() === 0)
                                <i>{{ $_lang['access_permissions_no_users_in_group'] }}</i>
                            @else
                                <?php /** @var EvolutionCMS\Models\ManagerUser $user */?>
                                @foreach($userGroup->users as $user)
                                    <a href="index.php?a=88&id={{ $user->getKey() }}">{{ $user->username }}</a>@if($loop->last === false), @endif
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="tab-page" id="tabPage2">
            <h2 class="tab">{{ $_lang['access_permissions_resource_groups'] }}</h2>
            <script type="text/javascript">tp1.addTabPage(document.getElementById("tabPage2"));</script>

            <div class="container container-body">
                <p class="element-edit-message-tab alert alert-warning">{{ $_lang['access_permissions_resources_tab'] }}</p>
                <div class="form-group">
                    <b>{{ $_lang['access_permissions_add_resource_group'] }}</b>
                    <form method="post" action="index.php" name="accesspermissions">
                        <input type="hidden" name="a" value="92" />
                        <input type="hidden" name="operation" value="add_document_group" />
                        <div class="input-group">
                            <input class="form-control" type="text" value="" name="newdocgroup" />
                            <div class="input-group-btn">
                                <input class="btn btn-success" type="submit" value="{{ $_lang['submit'] }}" />
                            </div>
                        </div>
                    </form>
                </div>
                @if($documentGroups->count() === 0)
                    <div class="text-danger">{{ $_lang['no_groups_found'] }}</div>
                @else
                    <?php /** @var EvolutionCMS\Models\DocumentgroupName $documentGroup */?>
                    @foreach($documentGroups as $documentGroup)
                        <div class="form-group">
                            <form method="post" action="index.php" name="accesspermissions">
                                <input type="hidden" name="a" value="92" />
                                <input type="hidden" name="groupid" value="{{ $documentGroup->getKey() }}" />
                                <input type="hidden" name="operation" value="rename_document_group" />
                                <div class="input-group">
                                    <input class="form-control" type="text" name="newgroupname" value="{{ $documentGroup->name }}" />
                                    <div class="input-group-btn">
                                        <input class="btn btn-secondary" type="submit" value="{{ $_lang['rename'] }}" />
                                        <input class="btn btn-danger" type="button" value="{{ $_lang['delete'] }}" onclick="deletegroup({{ $documentGroup->getKey() }},'documentgroup');" />
                                    </div>
                                </div>
                            </form>
                            {!! $_lang['access_permissions_resources_in_group'] !!}
                            @if($documentGroup->documents->count() === 0)
                                <i>{{ $_lang['access_permissions_no_resources_in_group'] }}</i>
                            @else
                                <?php /** @var EvolutionCMS\Models\SiteContent $document */?>
                                @foreach($documentGroup->documents as $document)
                                    <a href="index.php?a=3&id={{ $document->getKey() }}" title="{{ $document->pagetitle }}">{{ $document->getKey() }}</a>@if($loop->last === false), @endif
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        @if($documentGroups->count() > 0 && $userGroups->count() > 0)
            <div class="tab-page" id="tabPage3">
                <h2 class="tab">{{ $_lang['access_permissions_links'] }}</h2>
                <script type="text/javascript">tp1.addTabPage(document.getElementById("tabPage3"));</script>

                <div class="container container-body">
                    <p class="element-edit-message-tab alert alert-warning">{{ $_lang['access_permissions_links_tab'] }}</p>
                    <div class="form-group">
                        <b>{{ $_lang["access_permissions_group_link"] }}</b>
                        <form method="post" action="index.php" name="accesspermissions">
                            <input type="hidden" name="a" value="92" />
                            <input type="hidden" name="operation" value="add_document_group_to_user_group" />

                            {{ $_lang["access_permissions_link_user_group"] }}
                            <select name="usergroup">
                                <?php /** @var EvolutionCMS\Models\MembergroupName $userGroup */?>
                                @foreach($userGroups as $userGroup)
                                    <option value="{{ $userGroup->getKey() }}"> {{ $userGroup->name }}</option>
                                @endforeach
                            </select>

                            {{ $_lang["access_permissions_link_to_group"] }}
                            <select name="docgroup">
                                <?php /** @var EvolutionCMS\Models\DocumentgroupName $documentGroup */?>
                                @foreach($documentGroups as $documentGroup)
                                    <option value="{{ $documentGroup->getKey() }}"> {{ $documentGroup->name }}</option>
                                @endforeach
                            </select>

                            <input class="btn btn-success" type="submit" value="{{ $_lang['submit'] }}">
                        </form>
                    </div>
                    <hr>
                    <?php /** @var EvolutionCMS\Models\MembergroupName $userGroup */?>
                    @foreach($userGroups as $userGroup)
                        <ul>
                            <li>
                                <b>{{ $userGroup->name }}</b>
                                @if($userGroup->documentGroups->count() > 0)
                                    <ul>
                                        <?php /** @var EvolutionCMS\Models\DocumentgroupName $documentGroup */?>
                                        @foreach($userGroup->documentGroups as $documentGroup)
                                            <li>
                                                {{ $documentGroup->name }}
                                                <small><i>(<a class="text-danger" href="index.php?a=92&coupling={{ $documentGroup->getKey() }}&operation=remove_document_group_from_user_group">{{ $_lang['remove'] }}</a>)</i></small>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <i>{{ $_lang['no_groups_found'] }}</i>
                                @endif
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
