<template id="form-user-template">
  <div class="modal" id="user_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit User' : 'New User'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Name
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                        <strong>@{{ formErrors['name'][0] }}</strong>
                    </span>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Email
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <input type="text" name="email" class="form-control" v-model="form.email" :class="{ 'is-invalid' : formErrors['email'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['email']">
                        <strong>@{{ formErrors['email'][0] }}</strong>
                    </span>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Phone Number
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <input type="text" name="phone_number" class="form-control" v-model="form.phone_number" :class="{ 'is-invalid' : formErrors['phone_number'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['phone_number']">
                        <strong>@{{ formErrors['phone_number'][0] }}</strong>
                    </span>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                          <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Password
                            </label>
                            <input type="password" name="password" class="form-control" v-model="form.password" :class="{ 'is-invalid' : formErrors['password'] }" v-bind:placeholder="form.id ? 'Remain blank to use the same password' : ''">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['password']">
                                <strong>@{{ formErrors['password'][0] }}</strong>
                            </span>
                          </div>
                          <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Password Confirmation
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" v-model="form.password_confirmation" :class="{ 'is-invalid' : formErrors['password_confirmation'] }" v-bind:placeholder="form.id ? 'Remain blank to use the same password' : ''">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['password_confirmation']">
                                <strong>@{{ formErrors['password_confirmation'][0] }}</strong>
                            </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                    <button type="submit" class="btn btn-success" v-if="!form.id">Create</button>
                    <button type="submit" class="btn btn-success" v-if="form.id">Save</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                  </div>
              </div>
              </form>
          </div>
      </div>
  </div>
</template>