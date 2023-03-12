@php $restoreTime = getConfigValueByName("CONF_RESTORE_SCHEDULE_TIME"); @endphp
<script>
    
    /* $(document).on("click", "#demoBoxClose", function(e) {
         $('.demo-header').hide();
         $('html').removeClass('sticky-demo-header');
     });*/

    // Set the date we're counting down to
    var countDownDate = new Date('{{$restoreTime}}').getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        //var now = new Date().getTime();
        var currentTime = new Date();
        var currentOffset = currentTime.getTimezoneOffset();
        var ISTOffset = 330; // IST offset UTC +5:30
        var now = new Date(currentTime.getTime() + (ISTOffset + currentOffset) * 60000);

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var str = ('0' + hours).slice(-2) + ":" + ('0' + minutes).slice(-2) + ":" + ('0' + seconds).slice(-2);
        // Display the result in the element with id="demo"
        document.getElementById("restoreCounter").innerHTML = str;
        //$('#restoreCounter').html(str);
        var progressPercentage = 100 - (parseFloat(hours + '.' + parseFloat(minutes / 15 * 25)) * 100 / 4);

        $('.restore__progress-bar').css('width', progressPercentage + '%');
        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            $('#restoreCounter').html("Process...");
            $('#modalRetore').modal("show");

            restoreSystem();
        }
    }, 1000);

    function restoreSystem() {
        $.post("{{route('restoreDatabase')}}", function(response) {
           
        });
        setTimeout(function(){ window.open(window.location.href,'_self'); }, 9000);
    }
</script>