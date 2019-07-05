<template id="form-service-template">
  <div class="modal" id="service_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              	<div class="modal-header back-happyrent-light-green text-white">
                  	<div class="modal-title">
                      Service accounts
                  	</div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              	</div>
              	<div class="modal-body">
              		<div class="row back-happyrent-light-green text-white">
              			<div class="col-md-3">Electricity</div>
              			<div class="col-md-3">Water</div>
              			<div class="col-md-3">Broadband</div>
              		</div>

              		<ul class="nav nav-pills">
						<li class="active"><a data-toggle="pill" href="#elec">Electricity</a></li>
					   	<li><a data-toggle="pill" href="#water">Water</a></li>
					   	<li><a data-toggle="pill" href="#broad">Broadband</a></li>
					</ul>
					<div class="tab-content">
					    <div id="elec" class="tab-pane fade in active">
					      	<div class="col-md-12 col-sm-12 col-xs-12">
		                      	<div class="form-row">
			                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
			                            <label class="control-label">
			                               	<select class="form-control" v-model="service.service_type">
			                               		<option></option>
			                               	</select>
			                            </label>
			                        </div>
		                      	</div>
		                  	</div>
		                  	<div class="col-md-12 col-sm-12 col-xs-12">
		                      	<div class="form-row">
			                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
			                            <label class="control-label">
			                                Account number
			                            </label>
			                            <input type="text" name="block_number" class="form-control" v-model="service.account_number">
			                        </div>
		                      	</div>
		                  	</div>
					    </div>
					    <div id="water" class="tab-pane fade">
					      	<div class="col-md-12 col-sm-12 col-xs-12">
		                      	<div class="form-row">
			                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
			                            <label class="control-label">
			                               	<select class="form-control" v-model="service.service_type">
			                               		<option></option>
			                               	</select>
			                            </label>
			                        </div>
		                      	</div>
		                  	</div>
		                  	<div class="col-md-12 col-sm-12 col-xs-12">
		                      	<div class="form-row">
			                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
			                            <label class="control-label">
			                                Account number
			                            </label>
			                            <input type="text" name="block_number" class="form-control" v-model="service.account_number">
			                        </div>
		                      	</div>
		                  	</div>
					    </div>
					    <div id="broad" class="tab-pane fade">
					     	<div class="col-md-12 col-sm-12 col-xs-12">
		                      	<div class="form-row">
			                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
			                            <label class="control-label">
			                               	<select class="form-control" v-model="service.service_type">
			                               		<option></option>
			                               	</select>
			                            </label>
			                        </div>
		                      	</div>
	                  		</div>
		                  	<div class="col-md-12 col-sm-12 col-xs-12">
		                      	<div class="form-row">
			                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
			                            <label class="control-label">
			                                Account number
			                            </label>
			                            <input type="text" name="block_number" class="form-control" v-model="service.account_number">
			                        </div>
		                      	</div>
		                  	</div>
					    </div>
					</div>
              	</div>
              	<div class="modal-footer">
                  <div class="btn-group">
                    <button type="submit" class="btn btn-success" v-if="!service.id">Create</button>
                    <button type="submit" class="btn btn-success" v-if="service.id">Save</button>
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                  </div>
              	</div>
            </form>
          </div>
      </div>
  </div>
</template>