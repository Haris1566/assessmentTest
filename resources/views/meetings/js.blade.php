<script>
    $("#attendees").select2({
        dropdownParent: $("#meetingModal"),
        width: "100%",
    });

    function redirectPage(page) {
        console.log(page);
        var redirect = "{{ route('meetings.index',['page'=>'_page']) }}";
        redirect = redirect.replace('_page', page);
        console.log(redirect);
        window.location.href = redirect;
    }

    function clear_fields() {
        $("#add_meeting_form")[0].reset();
        $(".error").text('');
        $("#attendees").select2();
    }

    $(document).on('submit', '#add_meeting_form', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('meetings.store') }}",
            type: "POST",
            data: $(this).serialize(),
            beforeSend: function () {
                $("#submit_btn").text('Processing....');
                $("#submit_btn").prop('disabled', true);
            },
            success: function (data) {
                $("#submit_btn").text('Save Meeting');
                $("#submit_btn").prop('disabled', false);
                if (data.success) {
                    window.location.reload();
                    clear_fields();
                    alertify.success("Meeting Stored Successfully!");
                } else {
                    alertify.error("Something Went Wrong!");

                }
            },
            error: function (error) {
                $("#submit_btn").text('Save Meeting');
                $("#submit_btn").prop('disabled', false);
                if ('errors' in error.responseJSON) {
                    $(".error").text('');
                    $.each(error.responseJSON.errors, (index, value) => {
                        $(document).find("#" + index + "_error").text(value[0]);
                    });
                }
            }
        });
    });
    $(document).on('click', '.edit_meeting', function () {
        var edit_id = $(this).data('id');
        var currentPage = 1//$(this).data('page');
        var url = "{{ route('meetings.edit', [':id',':page']) }}";
        url = url.replace(':id', edit_id);
        url = url.replace(':page', currentPage);
        $.ajax({
            url: url,
            type: "GET",
            success: function (resonpse) {
                // console.log(resonpse);
                $("#editMeetingData").html(resonpse);
                $("#editMeetingModal").modal('toggle');
                setTimeout(() => {
                    $("#u_attendees").select2({
                        dropdownParent: $("#editMeetingModal"),
                        width: "100%",
                    }, 400);
                })
            }
        });
    });
    $(document).on('submit', '#update_meeting_form', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('meetings.update') }}",
            type: "POST",
            data: $(this).serialize(),
            beforeSend: function () {
                $("#update_btn").text('Processing...');
                $("#update_btn").prop('disabled', true);

            },
            error: function (error) {
                // console.log(error);
                $("#update_btn").text('Update Meeting');
                $("#update_btn").prop('disabled', false);
                if ('errors' in error.responseJSON) {
                    if ('errors' in error.responseJSON) {
                        $(".error").text('');
                        $.each(error.responseJSON.errors, (index, value) => {
                            $(document).find("#u_" + index + "_error").text(value[0]);
                        });
                    }
                }
            },
            success: function (data) {
                $("#update_btn").text('Update Meeting');
                $("#update_btn").prop('disabled', false);
                if (data.success) {
                    alertify.success("Meeting Updated Successfully!");
                    redirectPage(data.page);
                } else {
                    alertify.error("Something Went Wrong!");
                }
            }

        });
    });
    $(document).on('click', '.delete_meeting', function () {
        var del_id = $(this).data('id');
        var currentPage = $(this).data('page');
        var url = "{{ route('meetings.destroy', [':id',':page']) }}";
        url = url.replace(':id', del_id);
        url = url.replace(':page', currentPage);
        if (confirm('Are You Sure To Delete The Meeting?')) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.success) {
                        alertify.success("Meeting Deleted Successfully!");
                        redirectPage(response.page);
                    } else {
                        alertify.error("Something Went Wrong!");
                    }
                }
            });
        } else {
            return false;
        }

    });

</script>
