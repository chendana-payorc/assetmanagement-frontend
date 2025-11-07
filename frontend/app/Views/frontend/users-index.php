<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-heading d-flex justify-content-between">
    <h1 class="page-title">Admin List</h1>
    <!-- Trigger Offcanvas -->
    <button class="btn btn-primary my-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#addUserCanvas" aria-controls="addUserCanvas">
        Add User+
    </button>
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
                <tbody>
                    <tr>
                        <td>Chandana</td>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="addUserCanvas" aria-labelledby="addUserCanvasLabel">
  <div class="offcanvas-header">
    <h5 id="addUserCanvasLabel">Create User</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form id="createUserForm">
       
            <div class="form-group">
                <label class="required">Name</label>
                <input class="form-control" type="text" name="name" placeholder="Enter Name">
            </div>
        <div class="row">
        <div class="col-sm-6 form-group">
                <label>Role</label>
                <input class="form-control" type="text" name="role" placeholder="Enter Role">
            </div>
            <div class="col-sm-6 form-group">
                <label>Department</label>
                <input class="form-control" type="text" name="department" placeholder="Enter Department">
            </div>
        </div>

           <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" placeholder="Enter Email">
            </div>
            <div class="form-group">
    <label>Phone</label>
    <div class="input-group">
        <span class="input-group-text" style="width: 80px;">
            <select class="form-select border-0" name="country_code" style="padding: 0; width: 100%;">
                <option value="+91" selected>+91</option>
                <option value="+1">+1</option>
                <option value="+44">+44</option>
                <option value="+61">+61</option>
                <option value="+971">+971</option>
                <option value="+81">+81</option>
            </select>
        </span>
        <input class="form-control" type="text" name="phone_number" placeholder="Enter Number">
    </div>
</div>

            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password" placeholder="Enter Password">
            </div>
           

        <div class="form-group mt-3">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
  </div>
</div>
    <script>
$('#createUserForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: '<?= base_url('users-store') ?>', 
        method: 'POST',
        data: $(this).serialize(),
        success: function(response){
            alert('User added successfully!');
            $('#addUserCanvas').offcanvas('hide');
            location.reload();
        },
        error: function(){
            alert('Something went wrong!');
        }
    });
});
</script>
<?= $this->endSection() ?>

