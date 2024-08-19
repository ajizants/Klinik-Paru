            <!-- /.footer -->
            <footer class="main-footer">
                <strong>Copyright &copy; {{ date('Y') }} <a href="#">Klinik Utama Kesehatan Paru Masyarakat
                        Kelas
                        A</a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block">
                    <b>Versi</b> {{ env('APP_LARAVEL_VERSION') }}
                </div>
            </footer>


            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('actionlogout') }}">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
