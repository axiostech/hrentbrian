<template id="form-tenant-utilityrecord-template">
  <div class="modal" id="tenant_utilityrecord_modal">
      <div class="modal-dialog modal-lg">
          <form @submit.prevent="onSubmit" method="POST" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Utility Record' : 'New Utility Record'}}
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
                      Photo
                  </label>
                  <label for="required" class="control-label" style="color:red;">*</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" name="logo_url" class="custom-file-input" id="image" v-on:change="onFileChange" :class="{ 'is-invalid' : formErrors['image'] }">
                      <label class="custom-file-label" for="image">Choose file</label>
                    </div>
                  </div>
                  @{{file_name}}
                  <span class="invalid-feedback" role="alert" v-if="formErrors['image']">
                      <strong>@{{ formErrors['image'][0] }}</strong>
                  </span>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                  <label class="control-label">
                      Reading
                  </label>
                  <input type="text" name="reading" class="form-control" v-model="form.reading">
{{--
                  <span class="invalid-feedback" role="alert" v-if="formErrors['reading']">
                      <strong>@{{ formErrors['reading'][0] }}</strong>
                  </span> --}}
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                  <label class="control-label">
                    Month Year
                  </label>
                  <label for="required" class="control-label" style="color:red;">*</label>
                  <select2 v-model="form.monthyear">
                      <option value=""></option>
                      <option v-for="monthname in monthyear_options" :value="monthname.id">
                        @{{monthname.name}}
                      </option>
                  </select2>
                  <span class="invalid-feedback" role="alert" v-if="formErrors['monthyear']">
                      <strong>@{{ formErrors['monthyear'][0] }}</strong>
                  </span>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                  <label class="control-label">
                      Utility Type
                  </label>
                  <label for="required" class="control-label" style="color:red;">*</label>
                  <select2 v-model="form.type">
                    <option value=""></option>
                    @foreach(config('constant_utilityrecord.utilityrecord_type') as $type)
                      <option value="{{$type['id']}}">
                        {{$type['name']}}
                      </option>
                    @endforeach
                  </select2>
                  <span class="invalid-feedback" role="alert" v-if="formErrors['type']">
                      <strong>@{{ formErrors['type'][0] }}</strong>
                  </span>
                </div>
                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                  <label class="control-label">
                      Remarks
                  </label>
                  <textarea name="remarks" class="form-control" rows="2" v-model="form.remarks"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                    <button type="submit" class="btn btn-success" v-if="!form.id">Create</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                  </div>
              </div>
            </form>
          </div>
      </div>
  </div>
</template>