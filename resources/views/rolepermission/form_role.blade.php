<template id="form-role-template">
  <div class="modal" id="role_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header back-happyrent-light-green text-white">
                  <div class="modal-title">
                      @{{form.id ? 'Edit Role' : 'New Role'}}
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <label class="control-label">
                          Name
                      </label>
                      <input type="text" name="name" class="form-control" v-model="form.name">
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