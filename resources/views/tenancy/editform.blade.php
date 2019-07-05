{{-- <template id="edit-tenancy-template"> --}}
  <div class="modal" id="edit_tenancy_modal">
      <div class="modal-dialog modal-lg">
          <form action="#" @submit.prevent="onSubmit" method="POST" autocomplete="off">
          <div class="modal-content">
              <div class="modal-header text-white">
                  <div class="modal-title">
                    Tenancy Agreement
                  </div>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 pt-3">
                  <table class="table table-bordered">
                      <tr>
                          <th scope="row">
                            Tenancy ID
                          </th>
                          <td>
                              @{{editform.tenancy_code}}
                          </td>
                      </tr>
                      <tr>
                        <th scope="row">
                        Room Name
                        </th>
                        <td>
                            @{{editform.room_name}}
                        </td>
                      </tr>
                      <tr>
                          <th scope="row">
                            Date of Agreement
                          </th>
                          <td>
                              @{{editform.tenancy_date}}
                          </td>
                      </tr>
                      <tr v-if="editform.profile_id">
                          <th scope="row">
                              Created By
                          </th>
                          <td>
                            @{{editform.profile_name}}
                            <span v-if="editform.profile_roc">
                                <small>
                                    (@{{editform.profile_roc}})
                                </small>
                            </span>
                            <br>
                            @{{editform.profile_address}}
                          </td>
                      </tr>
                      <tr v-if="editform.tenant_id">
                          <th scope="row">
                              Tenant
                          </th>
                          <td>
                            @{{editform.tenant_name}}
                            <br>
                            @{{editform.tenant_idtype_name}}&nbsp;
                            @{{editform.tenant_idvalue}}
                          </td>
                      </tr>
                      <tr v-if="editform.beneficiary_id">
                          <th scope="row">
                              Landlord
                          </th>
                          <td>
                            @{{editform.beneficiary_name}}
                            <br>
                            @{{editform.beneficiary_idtype_name}}&nbsp;
                            @{{editform.beneficiary_idvalue}}
                          </td>
                      </tr>
                      <tr>
                          <th scope="row">
                              Demised Premises
                          </th>
                          <td>
                              <span>
                                  @{{editform.property_name}}
                              </span>
                              <br>
                              <span>
                                  @{{editform.unit_blocknumber}} @{{editform.unit_unitnumber}} @{{editform.unit_address}}
                              </span>
                          </td>
                      </tr>
                      <tr>
                          <th scope="row">
                              Duration of Tenancy
                          </th>
                          <td>
                              @{{editform.tenancy_durationmonth}} Month(s)
                              <br>
                              @{{editform.tenancy_datefrom}} (Commencement)
                              <br>
                              @{{editform.tenancy_dateto}} (Termination)
                          </td>
                      </tr>
                      <tr>
                          <th scope="row">
                              Rental
                          </th>
                          <td>
                              RM @{{editform.tenancy_rental}} (per Month)
                              <br>
                              <span v-if="editform.tenancy_deposit > 0">
                                RM @{{editform.tenancy_deposit}} (Deposit)
                              </span>
                          </td>
                      </tr>
                  </table>

                  <table class="table table-bordered">
                      <tr>
                          <th scope="row">
                              ARC Status
                          </th>
                          <td>
                            <div v-if="editform.arc_status === 1">
                                <span class="badge badge-success">
                                    ARC Link Sent
                                </span>
                                <i class="fas fa-long-arrow-alt-right"></i>
                                <span class="badge badge-secondary">
                                    Pending Tenant
                                </span>
                            </div>
                            <div v-else-if="editform.arc_status === 2">
                                <span class="badge badge-success">
                                    Tenant Viewed
                                </span>
                                <i class="fas fa-long-arrow-alt-right"></i>
                                <span class="badge badge-secondary">
                                    Pending Tenant
                                </span>
                            </div>
                            <div v-else-if="editform.arc_status === 3">
                                <span class="badge badge-success">
                                    Tenant Approved
                                </span>
                                <i class="fas fa-long-arrow-alt-right"></i>
                                <span class="badge badge-info">
                                    Pending Bank
                                </span>
                            </div>
                            <div v-else-if="editform.arc_status === 4">
                                <span class="badge badge-danger">
                                    Tenant Unsuccessful
                                </span>
                                <i class="fas fa-long-arrow-alt-right"></i>
                                <span class="badge badge-secondary">
                                    Pending Tenant
                                </span>
                            </div>
                            <div v-else-if="editform.arc_status === 5">
                                <span class="badge badge-success">
                                    Bank Approved (Collection Enabled)
                                </span>
                            </div>
                            <div v-else-if="editform.arc_status === 6">
                                <span class="badge badge-danger">
                                    Bank Rejected (Create again)
                                </span>
                            </div>
                            <div v-else>
                                <span class="badge badge-secondary">
                                    Non ARC
                                </span>
                            </div>
                          </td>
                      </tr>
                  </table>

                  <table class="table table-bordered">
                    <tr>
                        <th scope="row">
                            ARC
                        </th>
                        <td>
                        <button class="btn btn-outline-secondary btn-sm" v-if="editform.arc_status <= 2 || editform.arc_status == 4 |  !editform.arc_status" @click.prevent="registerARC(editform.tenancy_id, 'email')">
                            <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm" v-if="editform.arc_status <= 2 || editform.arc_status == 4 || !editform.arc_status" @click.prevent="registerARC(editform.tenancy_id, 'sms')">
                            SMS
                        </button>
                        <button class="btn btn-outline-success btn-sm" v-if="editform.arc_status <= 2 || editform.arc_status == 4 || !editform.arc_status" @click.prevent="sendArcWhatsapp(editform.tenancy_id)">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Utilities Record
                        </th>
                        <td>
                            <button class="btn btn-outline-success btn-sm" @click.prevent="sendUtilityRecordWhatsapp(editform.tenancy_id)">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Upload Tenancy Agreement
                            <br>
                            (.pdf .docx .jpg .png)
                        </th>
                        <td>
                            <div class="input-group pt-1 mb-1 border border-dark" v-if="editform.agreement_url">
                                <img v-bind:src="editform.agreement_url" class="img-fluid" v-if="getFileExtensionFilter(editform.agreement_url) == 'img'" width="400" height="300"/>
                                <embed v-bind:src="editform.agreement_url" width="400" height="300" type='application/pdf' v-if="getFileExtensionFilter(editform.agreement_url) == 'pdf'">
                            </div>
                            <form @submit.prevent="onAgreementUpload(editform.tenancy_id)" method="POST" enctype="multipart/form-data">
                                <div class="input-group input-group-sm">
                                    <input type="file" name="agreement" class="form-control" @change="onAgreementChosen($event)" >
                                    <div class="input-group-append">
                                        <a :href="editform.agreement_url" class="btn btn-info btn-sm" v-if="editform.agreement_url" download>
                                            <i class="fas fa-download"></i>
                                            Download
                                        </a>
                                        <button type="submit" class="btn btn-success btn-sm" :disabled="!is_file_selected">
                                            <i class="far fa-check-circle" ></i>
                                            <span v-if="editform.agreement_url">
                                                Replace
                                            </span>
                                            <span v-else>
                                                Upload
                                            </span>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" v-if="editform.agreement_url" @click="removeAgreementUrl(editform.tenancy_id)">
                                            <i class="fas fa-times"></i>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                  </table>
              </div>
              <div class="modal-footer">
                  <div class="btn-group">
                    {{-- <button type="submit" class="btn btn-success" v-if="form.id">Save</button> --}}
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Close</button>
                  </div>
              </div>
              </form>
          </div>
      </div>
  </div>
{{-- </template> --}}