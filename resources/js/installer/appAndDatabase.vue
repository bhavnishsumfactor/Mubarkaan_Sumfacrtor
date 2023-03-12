<template>
	<div class="card">
		<div class="card_body">
			<div class="steps-data">
				<div class="data form form-floating active">
					<h6>{{ $t('Storage permissions') }}</h6>
					<div class="row justify-content-center">
						<div class="col-md-12">
							<ul class="list-permission">
								<li class="list-permission-item"> <span class="lt"> {{ $t('storage/framework/') }} </span> <span class="rt">
                                    <strong>775</strong>
                                    <i class="animated-icn">
                                        <svg class="permission-icon animate" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#73AF55"
                                                stroke-width="6" stroke-miterlimit="10" cx="65.1"
                                                cy="65.1" r="62.1"></circle>
                                            <polyline class="path check" fill="none"
                                                stroke="#73AF55" stroke-width="6"
                                                stroke-linecap="round" stroke-miterlimit="10"
                                                points="100.2,40.2 51.5,88.8 29.8,67.5 "></polyline>
                                        </svg>
                                    </i>
                                </span> </li>
								<li class="list-permission-item"> <span class="lt"> {{ $t('storage/logs/') }}</span> <span class="rt">
                                    <strong> 775 </strong>
                                    <i class="animated-icn">
                                        <svg class="permission-icon animate" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#73AF55"
                                                stroke-width="6" stroke-miterlimit="10" cx="65.1"
                                                cy="65.1" r="62.1"></circle>
                                            <polyline class="path check" fill="none"
                                                stroke="#73AF55" stroke-width="6"
                                                stroke-linecap="round" stroke-miterlimit="10"
                                                points="100.2,40.2 51.5,88.8 29.8,67.5 "></polyline>
                                        </svg>
                                    </i>
                                </span> </li>
								<li class="list-permission-item"> <span class="lt"> {{ $t('bootstrap/cache/') }}</span> <span class="rt">
                                    <strong>
                                        775
                                    </strong>
                                    <i class="animated-icn">
                                        <svg class="permission-icon animate" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#73AF55"
                                                stroke-width="6" stroke-miterlimit="10" cx="65.1"
                                                cy="65.1" r="62.1"></circle>
                                            <polyline class="path check" fill="none"
                                                stroke="#73AF55" stroke-width="6"
                                                stroke-linecap="round" stroke-miterlimit="10"
                                                points="100.2,40.2 51.5,88.8 29.8,67.5 "></polyline>
                                        </svg>
                                    </i>
                                </span> </li>
							</ul>
							<h6>{{ $t('App Details') }}</h6>
							<div class="row justify-content-center">
								<div class="col-md-12">
									<div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-floating__group">
                                                <input v-bind:class="[errors.first('app_name') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.app_name != '' ? 'filled' : '']" type="text" name="app_name" id="app_name" v-model="installerData.app_name" v-validate="'required'" :data-vv-as="'App name '" data-vv-validate-on="none">
                                                <label class="form-floating__label" for="app_name"> {{ $t('App Name') }} <span class="required">*</span> </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-floating__group">
                                                <input v-bind:class="[errors.first('app_url') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.app_url != '' ? 'filled' : '']" type="url" name="app_url" id="app_url" v-model="installerData.app_url" v-validate="'required'" :data-vv-as="'App url'" data-vv-validate-on="none"  >
                                                <label class="form-floating__label" for="app_url"> {{ $t('App Url') }} <span class="required">*</span> </label>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
							<div class="row justify-content-center">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-floating__group">
												<input v-bind:class="[errors.first('database_name') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.db_name != '' ? 'filled' : '']" type="text" name="database_name" id="database_name" v-model="installerData.db_name" v-validate="'required'" :data-vv-as="'name'" data-vv-validate-on="none" >
												<label class="form-floating__label" for="database_name"> {{ $t('Database Name') }} <span class="required">*</span> </label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-floating__group">
												<input v-bind:class="[errors.first('database_hostname') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.db_host != '' ? 'filled' : '']" type="text" name="database_hostname" id="database_hostname" v-model="installerData.db_host" v-validate="'required'" :data-vv-as="'hostname'" data-vv-validate-on="none"> 
                                                
												<label class="form-floating__label" for="database_hostname"> {{ $t('Database Host') }} <span class="required">*</span> </label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-floating__group">
												<input v-bind:class="[errors.first('database_port') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.db_port != '' ? 'filled' : '']"  type="number" name="database_port" id="database_port" v-model="installerData.db_port" v-validate="'required'" :data-vv-as="'port'" data-vv-validate-on="none">

												<label class="form-floating__label" for="database_port"> {{ $t('Database Port') }} <span class="required">*</span> </label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group form-floating__group">
												<input v-bind:class="[errors.first('database_username') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.db_user != '' ? 'filled' : '']" type="text" name="database_username" id="database_username" v-model="installerData.db_user" v-validate="'required'" :data-vv-as="'username'" data-vv-validate-on="none">
												<label class="form-floating__label" for="database_username"> {{ $t('Database User Name') }} <span class="required">*</span> </label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group form-floating__group">
												<input v-bind:class="[errors.first('db_password') ? 'is-invalid' : '', 'form-control form-floating__field', installerData.db_password != '' ? 'filled' : '']" type="password" name="database_password" id="database_password" v-model="installerData.db_password">
												<label class="form-floating__label" for="database_password"> {{ $t('Database Password') }} </label>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-md-4" for="dummy_data"> {{ $t('Install Dummy Data') }} </label>
												<div class="col-md-8">
													<label class="radio" for="dummy_data_true">
														<input type="radio" name="dummy_data" id="dummy_data_true" value="1" v-model="installerData.install_dummy"> {{ $t('True') }} </label>
													<label class="radio" for="dummy_data_false">
														<input type="radio" name="dummy_data" id="dummy_data_false" value="0" v-model="installerData.install_dummy"> {{ $t('False') }} </label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card_foot">
			<button class="btn btn-outline-secondary btn-wide" @click="previous()">{{ $t('Back') }}</button>
			<button class="btn btn-brand btn-wide" @click="next()" :disabled="installerData.app_name == '' || installerData.app_url == '' || installerData.db_host == '' || installerData.db_port == '' || installerData.db_name == '' || installerData.db_user == ''">{{ $t('Next') }}</button>
		</div>
	</div>
</template>
<script>
export default {
    props: ["installerData"],
    data: function() {
        return { 
            baseUrl: baseUrl
        }
    },
    methods: {
        next: function() {
            this.$validator.validateAll().then(res => {
                if (res) {
                    //this.clicked = 1;
                    //let input = this.adminsData;
                    //this.clicked = 1;
                    this.$emit('next', 5, this.installerData);
                } else {
                    //this.clicked = 0;
                   toastr.error("Please fill the fields", "", toastr.options);
                }
            });
        },
        previous: function() {
            this.$emit('previous', 4, this.installerData);
        }
    }
}
</script>