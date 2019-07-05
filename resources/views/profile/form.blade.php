<template id="form-profile-template">
  <div class="modal" id="profile_modal">
      <div class="modal-dialog modal-lg">
          <form @submit.prevent="onSubmit" method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Profile' : 'New Profile'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                    <a :href="form.logo_url">
                        <img :src="form.logo_url" alt="No photo found" height="250" width="250" style="border:2px solid black">
                    </a>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Logo
                    </label>
                    <input type="file" name="logo_url" class="form-control-file" v-on:change="onFileChange">
                </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-8 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Profile Name
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="name" class="form-control" v-model="form.name" :class="{ 'is-invalid' : formErrors['name'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['name']">
                                <strong>@{{ formErrors['name'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                ROC
                            </label>
                            <input type="text" name="roc" class="form-control" v-model="form.roc">
                        </div>
                      </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <label class="control-label">
                          Address
                      </label>
                      <textarea name="address" class="form-control" rows="2" v-model="form.address"></textarea>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Postcode
                            </label>
                            <input type="text" name="postcode" class="form-control" v-model="form.postcode">
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                City
                            </label>
                            <input type="text" name="city" class="form-control" v-model="form.city">
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                State
                            </label>

                            <select2 v-model="form.state">
                                <option value="">All</option>
                                @foreach(config('constant_states.states') as $index => $state)
                                    <option value="{{$index}}">
                                    {{$state}}
                                    </option>
                                @endforeach
                            </select2>
                        </div>
                      </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Attn Name
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="attn_name" class="form-control" v-model="form.attn_name" :class="{ 'is-invalid' : formErrors['attn_name'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['attn_name']">
                                <strong>@{{ formErrors['attn_name'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Attn Phone Number
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="attn_phone_number" class="form-control" v-model="form.attn_phone_number" :class="{ 'is-invalid' : formErrors['attn_phone_number'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['attn_phone_number']">
                                <strong>@{{ formErrors['attn_phone_number'][0] }}</strong>
                            </span>
                        </div>
                      </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Prefix
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="prefix" class="form-control" v-model="form.prefix" :class="{ 'is-invalid' : formErrors['prefix'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['prefix']">
                                <strong>@{{ formErrors['prefix'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Email
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="email" class="form-control" v-model="form.email" :class="{ 'is-invalid' : formErrors['email'] }">
                            <span class="invalid-feedback" role="alert" v-if="formErrors['email']">
                                <strong>@{{ formErrors['email'][0] }}</strong>
                            </span>
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Domain Name
                            </label>
                            <input type="text" name="domain_name" class="form-control" v-model="form.domain_name">
                        </div>
                      </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" v-model="form.user_id" :disabled="!form.email">
                        <label class="form-check-label">
                            Create Company Login?
                        </label>
                        <p>
                            <small>
                                *By checking this checkbox, company is able to login to the system as a user.
                                <span style="color:red;">(Make sure email address has been filled)</span>
                            </small>
                        </p>
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