<template id="form-property-template">
  <div class="modal" id="property_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Property' : 'New Property'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-9 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Name
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="name" class="form-control" v-model="form.name">
                        </div>
                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label">
                                PTD Code
                            </label>
                            <input type="text" name="ptd_code" class="form-control" v-model="form.ptd_code">
                        </div>
                      </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <label class="control-label">
                          Address
                      </label>
                      <textarea name="address" class="form-control" rows="3" v-model="form.address"></textarea>
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
                              <label for="required" class="control-label" style="color:red;">*</label>
                              <input type="text" name="city" class="form-control" v-model="form.city">
                              {{-- <span class="help is-danger" v-if="form.errors.has('city')" v-text="form.errors.get('city')"></span> --}}
                          </div>
                          <div class="form-group col-md-4 col-sm-6 col-xs-12">
                              <label class="control-label">
                                  State
                              </label>
                              <label for="required" class="control-label" style="color:red;">*</label>
                              <input type="text" name="state" class="form-control" v-model="form.state">
                              {{-- <span class="help is-danger" v-if="form.errors.has('state')" v-text="form.errors.get('state')"></span> --}}
                          </div>
                      </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Attn Name
                                </label>
                                <input type="text" name="attn_name" class="form-control" v-model="form.attn_name">
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">
                                    Attn Phone Number
                                </label>
                                <input type="text" name="attn_phone_number" class="form-control" v-model="form.attn_phone_number">
                            </div>
                      </div>
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">
                            Property Type
                        </label>
                        <select2 v-model="form.type_id">
                            <option value="">All</option>
                            <option v-for="type in propertytype_options" :value="type.value">
                            @{{type.label}}
                        </select2>
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