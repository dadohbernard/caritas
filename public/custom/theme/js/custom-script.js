    $(document).ready(function() {

        if ($("footer").length) {
            var footerheight = $('footer').innerHeight();

            $('body').css('padding-bottom', footerheight);
        }

        if ($(".custom-nice-select").length) {
            $('.custom-nice-select').niceSelect();
        }

        $('#announcements-info-list-data li:first-child').addClass('active');
        $('.tab-content').hide();
        $('.tab-content:first').show();

        // Click function
        $('#announcements-info-list-data li').click(function() {
            $('#announcements-info-list-data li').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').hide();

            var activeTab = $(this).find('a').attr('href');
            $(activeTab).fadeIn();
            return false;
        });

        if ($('#curd-title-table,#agreement-table').length) {
            $('#curd-title-table,#agreement-table').dataTable({
                "searching": false,
                "paging": false,
                "bInfo": false
            });
        }
    });


