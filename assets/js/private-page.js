$(document).ready(function() {

  // DELETE category from database:
  $(document).on('click', '.delete', function() {
  	var id = $(this).data('id');
  	$.ajax({
  	  url: 'app/backend-logic.php',
  	  type: 'GET',
      dataType: "json",
  	  data: {
      	'delete': 1,
      	'id': id,
      },
      success: function(response) {
        // remove deleted category and all of its subcategories client-side:
        $.each(response, function(index, value) {
          $('span.delete[data-id="' + value + '"]').parent().remove();
        });
      }
  	});
  });

  // UPDATE category in database:
  var edit_id;
  var edit_category
  $(document).on('click', '.edit', function() {
  	edit_id = $(this).data('id');
  	edit_category = $(this).parent();
  	// grab the category to be editted:
  	var title = $(this).siblings('.category-title').text();
  	var description = $(this).siblings('.category-description').text();
  	// place category info into form:
  	$('#title').val(title);
  	$('#description').val(description);
  	$('#submit_btn').hide();
  	$('#update_btn').show();
    $('#category-modal').css('display', 'block');
  });
  $(document).on('click', '#update_btn', function() {
    $('#category-modal').css('display', 'none');
    var id = edit_id;
  	var title = $('#title').val();
  	var description = $('#description').val();
  	$.ajax({
      url: 'app/backend-logic.php',
      type: 'POST',
      data: {
      	'update': 1,
      	'id': id,
      	'title': title,
      	'description': description,
        'parent_id': edit_category.data('parent_id'),
        'nesting_lvl': edit_category.data('nesting_lvl'),
      },
      success: function(response) {
      	$('#title').val('');
      	$('#description').val('');
      	$('#submit_btn').show();
      	$('#update_btn').hide();
      	edit_category.replaceWith(response);
      }
  	});
  });

  // SUBCATEGORY creation:
  var new_id = 0;
  var new_nesting_lvl = 0;
  var new_index = -1;
  $(document).on('click', '.new', function() {
    new_id = $(this).data('id');
    new_nesting_lvl = $(this).parent().data('nesting_lvl') + 1;
    new_index = $(this).parent().index();
  	$('#title').val('');
  	$('#description').val('');
  	$('#update_btn').hide();
  	$('#submit_btn').show();
    $('#category-modal').css('display', 'block');
  });
  // SAVE new category into database:
  $(document).on('click', '#submit_btn', function() {
    var id = new_id;
    var title = $('#title').val();
    var description = $('#description').val();
    $.ajax({
      url: 'app/backend-logic.php',
      type: 'POST',
      data: {
        'save': 1,
        'title': title,
        'description': description,
        'parent_id': id,
        'nesting_lvl': new_nesting_lvl,
      },
      success: function(response) {
        $('#title').val('');
        $('#description').val('');
        if (new_index === -1) {
          $('#display-area').append(response);
        } else {
          $('#display-area .category-box').eq(new_index).after(response);
        }
        $('#category-modal').css('display', 'none');
        new_id = 0;
        new_index = -1;
        new_nesting_level = 0;
      }
    });
  });

  // LOG OUT realisation:
  $('.logout-btn').click(function() {
    $.ajax({
      type: "POST",
      url: "app/backend-logic.php",
      data: {
        logout: true
      }
    }).done(function() {
      window.location.href = "/index.php";
    });
  });

  // MODAL  functionality:
  var modal = document.getElementById('category-modal');
  var btn = document.getElementsByClassName("new-root-category")[0].getElementsByTagName('button')[0];
  var span = document.getElementsByClassName("close")[0];

  btn.onclick = function() {
    modal.style.display = "block";
  }

  span.onclick = function() {
    modal.style.display = "none";
  }

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

  // OFFSET creation for visually distinguishing categories and subcategories:
  var categoryBoxes = document.getElementsByClassName("category-box");

  var i = 0;
  for (i; i < categoryBoxes.length; i++) {
      var offset = categoryBoxes[i].dataset.nesting_lvl * 30;
      categoryBoxes[i].style.margin = "0 " + (5 - offset) + "px 0 " + (5 + offset) + "px";
  }
});

// MOBILE styling quick hack:
var smallScreen = window.matchMedia("(max-width: 480px)");
if (smallScreen.matches){
    $('.new-root-category').html('<button class="plus-sign"></button>');
    $('.new-root-category button').css({"top": "-20px"});
}
