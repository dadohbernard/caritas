/***************************************end**************************************************/

/***************************************PLACE DELETE LOGIC*****************************************/

$(document).on("click", ".delete-place", function () {
  var id = $(this).attr("data-id");
  var del_url = $(this).attr("data-url");
  swal({
    title: "Are you sure?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#22D69D",
    cancelButtonColor: "#FB8678",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
    confirmButtonClass: "btn",
    cancelButtonClass: "btn",
  }).then(function (result) {
    if (result.value) {
      $.ajaxSetup({
        headers: {
          "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
        },
      });
      $.ajax({
        type: "DELETE",
        dataType: "json",
        url: del_url,
        success: function (data) {
          if (data) {
            swal({
              title: "Success",
              text: "Deleted Successfully.",
              type: "success",
              confirmButtonColor: "#22D69D",
            });
            $("#manage-places").DataTable().draw();
          }
        },
      });
    }
  });
});
/***************************************END**************************************************/

/***************************************PLACE ACTIVION/DEACTIVIATION LOGIC*****************************************/

$(document).on("change", ".toggle-class", function () {
  var id = $(this).attr("data-id");
  var status_url = $(this).attr("data-url");
  if ($(this).is(":checked")) {
    var status = 1;
    var statusname = "Activate";
  } else {
    var status = 0;
    var statusname = "De-activate";
  }
  swal({
    title: "Are you sure want to " + statusname + "?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#22D69D",
    cancelButtonColor: "#FB8678",
    confirmButtonText: "Yes",
    cancelButtonText: "No",
    confirmButtonClass: "btn",
    cancelButtonClass: "btn",
  }).then(function (result) {
    if (result.value) {
      $.ajaxSetup({
        headers: {
          "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
        },
      });
      $.ajax({
        type: "POST",
        dataType: "json",
        url: status_url,
        data: {
          id: id,
          status: status,
        },
        success: function (data) {
          if (data) {
            swal({
              title: "Success",
              text: "Status Updated Successfully.",
              type: "success",
              confirmButtonColor: "#22D69D",
            });
            $("#manage-places").DataTable().draw();
          }
        },
      });
    } else {
      $("#manage-places").DataTable().draw();
    }
  });
});
/***************************************END**************************************************/

/*************************************Text Modal ***************************************/

$("#text_modal").on("show.bs.modal", function (e) {
  var id = $(e.relatedTarget).data("id");
  $.ajaxSetup({
    headers: {
      "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
    },
  });
  $("#add-texts").html("");
  $.ajax({
    type: "POST",
    url: "{{route('manage-text-list')}}", //Here you will fetch records
    data: "id=" + id, //Pass $id
    success: function (data) {
      if (data.texts.length === 0) {
        var i = 0;

        var elements = '<div class="card-body" id="' + i + '">';
        elements +=
          '<div class="form-group"><label for="place_name">{{ trans("Admin/place.place_name") }}<span class="text-danger">*</span></label><div id="place_name_' +
          i +
          '">' +
          data.placename.place_name +
          '</div><label for="category_name">{{ trans("Admin/place.place_title") }}<span class="text-danger">*</span></label>';

        elements +=
          '<input type="text" class="form-control" id="info_title_' +
          i +
          '" name="info_title[]" placeholder="Enter Title" value="">';

        elements +=
          '<input type="hidden" class="form-control" id="place_id_' +
          i +
          '" name="place_id[]" value="' +
          id +
          '"></div>';

        elements +=
          '<div class="form-group"><label for="category_details">{{ trans("Admin/place.place_discription") }}</label><textarea  cols="5" rows="5" class="form-control" id="info_description_' +
          i +
          '" name="info_description[]" placeholder="Enter  Details" ></textarea></div>';

        elements +=
          '<div class="form-group"><label for="place">{{ trans("Admin/place.status") }}</label>';

        elements +=
          '<select class="form-control status" id="status' +
          i +
          '" name="status[]"><option value="">{{ trans("Admin/place.select") }}</option>';

        elements +=
          '<option value="1">Active</option><option value="0">De-Active</option></select>';

        elements += "</div></div></div>";

        $("#add-texts").append(elements);
      } else {
        var rowIdx = 0;

        var i = 0;

        $.each(data.texts, function (key, val) {
          i++;
          var elements = '<div class="card-body" id="' + i + '">';
          elements +=
            '<div class="form-group"><label for="place_name">{{ trans("Admin/place.place_name") }}<span class="text-danger">*</span></label><div id="place_name_' +
            i +
            '">' +
            data.placename.place_name +
            '</div><label for="category_name">{{ trans("Admin/place.place_title") }}<span class="text-danger">*</span></label>';

          elements +=
            '<input type="text" class="form-control" id="info_title_' +
            i +
            '" name="info_title[]" placeholder="Enter Title" value="' +
            val.info_title +
            '">';

          elements +=
            '<input type="hidden" class="form-control" id="place_info_id_' +
            i +
            '" name="place_info_id[]" value="' +
            val.id +
            '">';

          elements +=
            '<input type="hidden" class="form-control" id="place_id_' +
            i +
            '" name="place_id[]" value="' +
            id +
            '"></div>';

          elements +=
            '<div class="form-group"><label for="category_details">{{ trans("Admin/place.place_discription") }}</label><textarea  cols="5" rows="5" class="form-control" id="info_description_' +
            i +
            '" name="info_description[]" placeholder="Enter  Details" >' +
            val.info_description +
            "</textarea></div>";

          elements +=
            '<div class="form-group"><label for="place">{{ trans("Admin/place.status") }}</label>';

          elements +=
            '<select class="form-control status" id="status' +
            i +
            '" name="status[]"><option value="">{{ trans("Admin/place.select") }}</option>';

          if (val.status == 1) {
            elements += '<option value="1" selected>Active</option>';
          } else {
            elements += '<option value="0" selected>De-Active</option>';
          }
          elements +=
            '<option value="1">Active</option><option value="0">De-Active</option></select>';

          elements += "</div></div></div>";

          $("#add-texts").append(elements);
        });
      }
      $("#add-texts").append(
        '<div class="form-group"><button type="button" class="btn btn-primary add-another-place-details" >+</button></div>'
      );

      $("#add-texts").append(
        '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button><input id="tag-form-submit" type="submit" class="btn btn-primary" value="Save"></div>'
      );
    },
  });
});

/***************************************END**************************************************/

$("#add-texts").on("click", ".add-another-place-details", function () {
  var row_index = $("#add-texts div.card-body:last").attr("id");
  var placename = $("#place_name_" + row_index).text();
  var placeid = $("#place_id_" + row_index).val();

  if (
    $("#info_title_" + row_index).val() &&
    $("#info_description_" + row_index).val()
  ) {
    var i = parseInt(row_index) + 1;

    var elements = '<div class="card-body" id="' + i + '">';
    elements +=
      '<div class="form-group"><label for="place_name">{{ trans("Admin/place.place_name") }}<span class="text-danger">*</span></label><div id="place_name_' +
      i +
      '">' +
      placename +
      '</div><label for="category_name">{{ trans("Admin/place.place_title") }}<span class="text-danger">*</span></label>';

    elements +=
      '<input type="text" class="form-control" id="info_title_' +
      i +
      '" name="info_title[]" placeholder="Enter Title" value="">';

    elements +=
      '<input type="hidden" class="form-control" id="place_id_' +
      i +
      '" name="place_id[]" value="' +
      placeid +
      '"></div>';

    elements +=
      '<div class="form-group"><label for="category_details">{{ trans("Admin/place.place_discription") }}</label><textarea  cols="5" rows="5" class="form-control" id="info_description_' +
      i +
      '" name="info_description[]" placeholder="Enter  Details" ></textarea></div>';

    elements +=
      '<div class="form-group"><label for="place">{{ trans("Admin/place.status") }}</label>';

    elements +=
      '<select class="form-control status" id="status' +
      i +
      '" name="status[]"><option value="">{{ trans("Admin/place.select") }}</option>';

    elements +=
      '<option value="1">Active</option><option value="0">De-Active</option></select>';

    elements += "</div></div></div>";

    $("#add-texts div.card-body:last").after(elements);
  } else {
    alert("Please fill above Place");
  }
});

$(document).ready(function () {
  $("#add-texts").validate({
    ignore: [],
    rules: {
      "info_title[]": {
        required: true,
      },
      "status[]": {
        required: true,
      },
    },
    messages: {
      "info_title[]": {
        required: "Please Enter title",
      },
      "status[]": {
        required: "Please Select Status",
      },
    },
    errorClass: "help-inline text-danger",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
      $(element).parents(".form-group").addClass("has-error");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).parents(".form-group").removeClass("has-error");
      $(element).parents(".form-group").addClass("has-success");
    },

    submitHandler: function (form, e) {
      e.preventDefault();
      console.log("Form submitted");
      $.ajaxSetup({
        headers: {
          "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
        },
      });
      $.ajax({
        url: "{{ route('manage-text-save') }}",
        type: "POST",
        dataType: "json",
        data: $("#add-texts").serialize(),
        success: function (result) {
          if (result.status == 200) {
            $("#resmsg").html("");
            $("#text_modal").modal("hide");
            window.location.href = "{{route('manage-place')}}";
          } else {
            $("#resmsg").html(
              '<div class="alert alert-danger text-center" role="alert">Something Went Wrong</div>'
            ); //show error message
          }
        },
        error: function (error) {},
      });
      return false;
    },
  });
});
