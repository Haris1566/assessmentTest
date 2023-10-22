<script>
    $("#attendees").select2({
        dropdownParent: $("#meetingModal"),
        width: "100%",
    });

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
        var url = "{{ route('meetings.edit', ':id') }}";
        url = url.replace(':id', edit_id);
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
    $(document).on('submit', '#update_product_category_form', function (e) {
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
                    window.location.reload();
                } else {
                    alertify.error("Something Went Wrong!");
                }
            }

        });
    });
    $(document).on('click', '.delete_meeting', function () {
        var del_id = $(this).data('id');
        var url = "{{ route('meetings.destroy', ':id') }}";
        url = url.replace(':id', del_id);
        if (confirm('Are You Sure To Delete The Product Category?')) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.success) {
                        alertify.success("Category Deleted Successfully!");
                        window.location.reload();
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
