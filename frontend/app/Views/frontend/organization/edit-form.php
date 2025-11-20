<input type="hidden" name="id" value="<?= esc($organization['id']) ?>">

<div class="form-group mb-3">
    <label class="required">Organization Name<span style="color:red">*</span></label>
    <input class="form-control" type="text" name="name" value="<?= esc($organization['name']) ?>" placeholder="Enter Name">
</div>

<div class="form-group mb-3">
    <label class="required">Email<span style="color:red">*</span></label>
    <input type="email" name="email" class="form-control" value="<?= esc($organization['email']) ?>" placeholder="Enter Email">
</div>

<div class="form-group mb-3">
    <label class="required">Contact No<span style="color:red">*</span></label>
    <input type="text" name="contact_no" class="form-control" value="<?= esc($organization['contact_no']) ?>" placeholder="Enter Contact No">
</div>

<div class="form-group mb-3">
    <label class="required">Address<span style="color:red">*</span></label>
    <input type="text" name="address" class="form-control" value="<?= esc($organization['address']) ?>" placeholder="Enter Address">
</div>

<div class="form-group mb-3">
    <label class="required">Country<span style="color:red">*</span></label>
    <input type="text" name="country" class="form-control" value="<?= esc($organization['country']) ?>" placeholder="Enter Country">
</div>

<div class="form-group mb-3">
    <label class="required">State<span style="color:red">*</span></label>
    <input type="text" name="state" class="form-control" value="<?= esc($organization['state']) ?>" placeholder="Enter State">
</div>

<div class="form-group mb-3">
    <label class="required">City<span style="color:red">*</span></label>
    <input type="text" name="city" class="form-control" value="<?= esc($organization['city']) ?>" placeholder="Enter City">
</div>

<div class="form-group mb-3">
    <label class="required">Zipcode<span style="color:red">*</span></label>
    <input type="text" name="zipcode" class="form-control" value="<?= esc($organization['zipcode']) ?>" placeholder="Enter Zipcode">
</div>
