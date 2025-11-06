<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-content fade-in-up">
<div class="row">
                    <div class="col-md-12">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Create Department</div>
                                
                            </div>
                            <div class="ibox-body">
                                <form>
                                   
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input class="form-control" type="text" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <input class="form-control" type="text" placeholder="First Name">
                                        </div>
                                     
                                     
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                 
                </div>
</div>
<?= $this->endSection() ?>