<template id="form-unit-template">
  <div class="modal" id="unit_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Unit' : 'New Unit'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Block Number
                            </label>
                            <input type="text" name="block_number" class="form-control" v-model="form.block_number">
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Unit Number
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="unit_number" class="form-control" v-model="form.unit_number">
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
                          <div class="form-group col-md-3 col-sm-6 col-xs-12">
                              <label class="control-label">
                                  Square Feet
                              </label>
                              <input type="text" name="squarefeet" class="form-control" v-model="form.squarefeet">
                          </div>
                          <div class="form-group col-md-3 col-sm-6 col-xs-12">
                              <label class="control-label">
                                  Purchase Price
                              </label>
                              <input type="text" name="purchase_price" class="form-control" v-model="form.purchase_price">
                          </div>
                          <div class="form-group col-md-3 col-sm-6 col-xs-12">
                              <label class="control-label">
                                  Purchase Date
                              </label>
                              <i class="far fa-calendar-alt"></i>
                              <datepicker v-model="form.purchased_at" format="yyyy-MM-dd" :bootstrap-styling="true" @input="onDateChanged('purchased_at')"></datepicker>
                          </div>
                          <div class="form-group col-md-3 col-sm-6 col-xs-12">
                              <label class="control-label">
                                  Bed and Bath Room
                              </label>
                              <input type="text" name="bedbath_room" class="form-control" v-model="form.bedbath_room">
                          </div>
                      </div>
                  </div>
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <label class="control-label">
                          Remarks
                      </label>
                      <textarea name="remarks" class="form-control" rows="3" v-model="form.remarks"></textarea>
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