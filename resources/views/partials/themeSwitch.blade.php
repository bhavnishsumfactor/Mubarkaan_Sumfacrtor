<script>
    if (typeof $.cookie(systemPrefix + 'ThemeStyle') != 'undefined') {
        if ($.cookie(systemPrefix + 'ThemeStyle') == 'light') {
            $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-lite'));
            $('.YK-themeSwitch').removeClass('dark').addClass('light');
            $('.YK-themeSwitch svg use').attr('xlink:href', $('.YK-themeSwitch').data('light'));
            $('.YK-themeSwitch svg use').attr('href', $('.YK-themeSwitch').data('light'));
            $('.YK-mode').html('Dark Mode');
        } else {
            if ($("#yk-header-logo").attr('data-dark') != "") {
                $('#yk-header-logo').attr('src', $("#yk-header-logo").attr('data-dark'));
            }
            $('.YK-themeSwitch').removeClass('light').addClass('dark');
            $('.YK-themeSwitch svg use').attr('xlink:href', $('.YK-themeSwitch').data('dark'));
            $('.YK-themeSwitch svg use').attr('href', $('.YK-themeSwitch').data('dark'));
            $('.YK-mode').html('Light Mode');
        }
    }
</script>