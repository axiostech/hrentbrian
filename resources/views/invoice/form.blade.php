<template id="form-invoice-template">
  <div class="modal" id="invoice_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Invoice' : 'New Invoice'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="form-row">
                        <div class="form-group col-md-8 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Invoice Number
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="invoice_number" class="form-control" v-model="form.invoice_number">
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
                            <input type="text" name="attn_name" class="form-control" v-model="form.attn_name">
                        </div>
                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Attn Phone Number
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="attn_phone_number" class="form-control" v-model="form.attn_phone_number">
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
                            <input type="text" name="prefix" class="form-control" v-model="form.prefix">
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Email
                            </label>
                            <label for="required" class="control-label" style="color:red;">*</label>
                            <input type="text" name="email" class="form-control" v-model="form.email">
                        </div>
                        <div class="form-group col-md-4 col-sm-6 col-xs-12">
                            <label class="control-label">
                                Domain Name
                            </label>
                            <textarea name="domain_name" class="form-control" rows="2" v-model="form.domain_name"></textarea>
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