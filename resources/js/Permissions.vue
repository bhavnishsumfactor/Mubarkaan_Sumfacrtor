<script>
  export default {
    methods: {
      	$canRead(permissionName) {
			if(window.Laravel.permissions['role_id'] == null || window.Laravel.permissions['role_id'].length <= 0) {
				return true;
			}
			if((window.Laravel.permissions[permissionName] == 1 || window.Laravel.permissions[permissionName] == 2) && window.Laravel.permissions.hasOwnProperty(permissionName) ) {
				return true;
			}
			return false;
      	},
	  	$canWrite(permissionName) {
			if(window.Laravel.permissions['role_id'] == null || window.Laravel.permissions['role_id'].length <= 0) {
				return true;
			}
			return window.Laravel.permissions[permissionName] == 2;
      	},
		$canAccessPageUrl(permissionName) {
			//check front end conditions
			/*if(window.Laravel.permissions.hasOwnProperty(permissionName) && window.Laravel.permissions[permissionName] == 0 ){
				this.$router.push({ name: 'unAuthorised'} );
			}else if(!window.Laravel.permissions.hasOwnProperty(permissionName)) {
				this.$router.push({ name: 'unAuthorised'} );
			}
			return true;*/
		},
		$canAccessModule(permissionName,permissionValue) {
			if(permissionValue == 0)
			{
				this.$router.push({ name: 'unAuthorised'} );
			}
		}
  	}
 };
</script>
