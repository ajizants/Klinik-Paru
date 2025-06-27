                {{-- input tindakan --}}
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Users</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2" id="usersContainer">
                            <div class="container-fluid form-row">
                                <button type="button" class="btn btn-success mx-2" id="refresh"
                                    onclick="cariUser()">Refresh</button>
                                @include('User.create')
                            </div>
                            {!! $users !!}
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body" id="editUserModalBody">
                            </div>
                        </div>
                    </div>
                </div>
