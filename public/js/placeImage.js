/*************************************Image Modal start ***************************************/   
 
    $('#image_modal').on('show.bs.modal', function (e) {
      var id = $(e.relatedTarget).data('id');
      $.ajaxSetup({
        headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $('#add-images').html('');
      $.ajax({
        type : 'POST',
            url : "{{route('manage-images-list')}}",//Here you will fetch records 
            data :  'id='+ id, //Pass $id
            success : function(data){
              if (data.texts.length === 0) {
                var i = 0;

                  var elements = '<div class="card-body" id="'+i+'">'; 
                elements += '<div class="form-group"><label for="place_name">{{ trans("Admin/place.place_name") }}<span class="text-danger">*</span></label><div id="place_name_'+i+'">'+data.placename.place_name+'</div><label for="category_name">{{ trans("Admin/place.place_title") }}<span class="text-danger">*</span></label>';

               elements += '<input type="text" class="form-control" id="info_title_'+i+'" name="info_title[]" placeholder="Enter Title" value="">';

               elements += '<input type="hidden" class="form-control" id="place_id_'+i+'" name="place_id[]" value="'+id+'"></div>';
              
               elements += '<div class="form-group"><label for="category_details">{{ trans("Admin/place.image_upload") }}</label><input type="file" id="image-upload" name="image_upload[]" enctype="multipart/form-data" multiple</div>';

               elements += '<div class="form-group"><label for="place">{{ trans("Admin/place.status") }}</label>'; 

               elements += '<select class="form-control status" id="status'+i+'" name="status[]"><option value="">{{ trans("Admin/place.select") }}</option>'; 
                
                elements += '<option value="1">Active</option><option value="0">De-Active</option></select>'; 

                elements += '</div></div></div>'; 

                $('#add-images').append(elements);
              }
              else
              {
               var rowIdx = 0;

               var i = 0;
               
               $.each(data.texts, function (key, val) {
                 i++;
                var elements = '<div class="card-body" id="'+i+'">'; 
                elements += '<div class="form-group"><label for="place_name">{{ trans("Admin/place.place_name") }}<span class="text-danger">*</span></label><div id="place_name_'+i+'">'+data.placename.place_name+'</div><label for="category_name">{{ trans("Admin/place.place_title") }}<span class="text-danger">*</span></label>';

               elements += '<input type="text" class="form-control" id="info_title_'+i+'" name="info_title[]" placeholder="Enter Title" value="'+val.info_title+'">';

               elements += '<input type="hidden" class="form-control" id="place_info_id_'+i+'" name="place_info_id[]" value="'+val.id+'">';

               elements += '<input type="hidden" class="form-control" id="place_id_'+i+'" name="place_id[]" value="'+id+'"></div>';
              
               elements += '<div class="form-group"><label for="category_details">{{ trans("Admin/place.place_discription") }}</label><textarea  cols="5" rows="5" class="form-control" id="info_description_'+i+'" name="info_description[]" placeholder="Enter  Details" >'+val.info_description+'</textarea></div>'; 

               elements += '<div class="form-group"><label for="place">{{ trans("Admin/place.status") }}</label>'; 

               elements += '<select class="form-control status" id="status'+i+'" name="status[]"><option value="">{{ trans("Admin/place.select") }}</option>'; 
                
                if(val.status == 1)
                {
                   elements += '<option value="1" selected>Active</option>';
                }else{
                   elements += '<option value="0" selected>De-Active</option>';
                }
                elements += '<option value="1">Active</option><option value="0">De-Active</option></select>'; 

                elements += '</div></div></div>'; 

                $('#add-images').append(elements);
                  
              });
             }
             $('#add-images').append('<div class="form-group"><button type="button" class="btn btn-primary add-another-place-details" >+</button></div>');

             $('#add-images').append('<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button><input id="tag-form-submit1" type="submit" class="btn btn-primary" value="Save"></div>');
           }
         });
    });


   
