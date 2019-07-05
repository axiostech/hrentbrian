<template id="form-tenant-template">
  <div class="modal" id="tenant_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Tenant' : 'New Tenant'}}
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
                        ID Type
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <select2 v-model="form.idtype_id">
                        <option value="">All</option>
                        <option v-for="idtype in idtype_options" :value="idtype.id">
                            @{{idtype.name}}
                        </option>
                    </select2>
                    <span class="invalid-feedback" role="alert" v-if="formErrors['idtype_id']">
                        <strong>@{{ formErrors['idtype_id'][0] }}</strong>
                    </span>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12" v-if="form.idtype_id">
                    <label class="control-label">
                        @{{idtype_options[form.idtype_id - 1].name}} Number
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <input type="text" name="id_value" class="form-control" v-model="form.id_value" :class="{ 'is-invalid' : formErrors['id_value'] }">
                    <span class="invalid-feedback" role="alert" v-if="formErrors['id_value']">
                        <strong>@{{ formErrors['id_value'][0] }}</strong>
                    </span>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-row">
                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Phone Number
                                </label>
                                <label for="required" class="control-label" style="color:red;">*</label>
                                <input type="text" name="phone_number" class="form-control" v-model="form.phone_number" :class="{ 'is-invalid' : formErrors['phone_number'] }">
                                <span class="invalid-feedback" role="alert" v-if="formErrors['phone_number']">
                                    <strong>@{{ formErrors['phone_number'][0] }}</strong>
                                </span>
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Alt Phone Number
                                </label>
                                <input type="text" name="alt_phone_number" class="form-control" v-model="form.alt_phone_number">
                            </div>
                            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Email
                                </label>
                                <input type="text" name="email" class="form-control" v-model="form.email">
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