<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

            <div class="page-heading d-flex justify-content-between">
                <h1 class="page-title">Admin List</h1>
                <a href="<?= base_url('users-create') ?>" style="cursor: pointer;line-height: 2.25;" class="btn btn-primary my-2">Add User+</a>
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                   
                    <div class="ibox-body">
                        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>Chendana</td>
                                    <td>user1</td>
                                    <td>chandana@gmail.com</td>
                                    <td>9886887979</td>
                                    <td>
                                    <button class="btn btn-sm btn-warning btn-circle"><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary btn-circle"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger btn-circle"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
               
            </div>

         
<?= $this->endSection() ?>