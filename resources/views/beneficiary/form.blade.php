<template id="form-beneficiary-template">
  <div class="modal" id="beneficiary_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Beneficiary' : 'New Beneficiary'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Name
                    </label>
                    <label for="required" class="control-label" style="color:red;">*</label>
                    <input type="text" name="name" class="form-control" v-model="form.name">
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                  <div class="form-row">
                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
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
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12" v-if="form.idtype_id">
                      <label class="control-label">
                          @{{idtype_options[form.idtype_id - 1].name}} Number
                      </label>
                      <label for="required" class="control-label" style="color:red;">*</label>
                      <input type="text" name="id_value" class="form-control" v-model="form.id_value">
                    </div>
                  </div>
                </div>
                <hr>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-row">
                      <div class="form-group col-md-4 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Phone Number
                          </label>
                          <label for="required" class="control-label" style="color:red;">*</label>
                          <input type="text" name="phone_number" class="form-control" v-model="form.phone_number">
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-row">
                      <div class="form-group col-md-4 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Gender
                          </label>
                          <select2 v-model="form.gender_id">
                              <option value="">All</option>
                              <option v-for="gender in gender_options" :value="gender.id">
                                  @{{gender.name}}
                              </option>
                          </select2>
                      </div>
                      <div class="form-group col-md-4 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Nationality
                          </label>
                          <select2 v-model="form.nationality_id">
                              <option value="">All</option>
                              <option v-for="nationality in nationality_options" :value="nationality.id">
                                  @{{nationality.name}}
                              </option>
                          </select2>
                      </div>
                      <div class="form-group col-md-4 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Race
                          </label>
                          <select2 v-model="form.race_id">
                              <option value="">All</option>
                              <option v-for="race in race_options" :value="race.id">
                                  @{{race.name}}
                              </option>
                          </select2>
                      </div>
                    </div>
                </div>
                <hr>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Address
                    </label>
                    <textarea name="address" class="form-control" rows="2" v-model="form.address">
                    </textarea>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-row">
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
                          <input type="text" name="state" class="form-control" v-model="form.state">
                      </div>
                      <div class="form-group col-md-4 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Postcode
                          </label>
                          <input type="text" name="postcode" class="form-control" v-model="form.postcode">
                      </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-row">
                      <div class="form-group col-md-6 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Occupation
                          </label>
                          <input type="text" name="occupation" class="form-control" v-model="form.occupation">
                      </div>
                      <div class="form-group col-md-6 col-sm-6 col-xs-12">
                          <label class="control-label">
                              Property Num (Invest)
                          </label>
                          <input type="text" name="invest_property_num" class="form-control text-right" v-model="form.invest_property_num">
                      </div>
                    </div>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                    <label class="control-label">
                        Remarks
                    </label>
                    <textarea name="remarks" class="form-control" rows="2" v-model="form.remarks">
                    </textarea>
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