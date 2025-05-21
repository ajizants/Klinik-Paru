                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseCardExample" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Users Log Login</h4>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseCardExample">
                        <div class="card-body p-2" id="usersOnlineContainer">
                            <div class="container">
                                <button type="button" class="btn btn-primary" id="refresh"
                                    onclick="cariLogUser()">Refresh</button>
                            </div>
                            {!! $usersOnline !!}
                        </div>
                    </div>
                </div>
