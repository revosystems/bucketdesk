var sortOptions = {
    connectWith: "ul",
    stop: function( event, ui ) {
        console.log(ui.item.index(), ui.item.attr('id').replace('issue_', ''), ui.item.parent().attr('id'));
        console.log(ui.item.parent().sortable('serialize', { key: "sort" }));
        $.ajax({
            url : updateUrl,
            method : 'PUT',
            data : {
                '_token': csrf_token,
                'id': ui.item.attr('id').replace('issue_', ''),
                'status': ui.item.parent().attr('id'),
            },
            success: function(result) {
                console.log('issue updated');
            },
            error: function(request,msg,error) {
                console.log('error updating issue');
            }
        });

        $.ajax({
            url : updateUrl,
            method : 'PUT',
            data : {
                '_token': csrf_token,
                'order': ui.item.index(),
                'id': ui.item.attr('id').replace('issue_', ''),
                'status': ui.item.parent().attr('id'),
                'sort' : ui.item.parent().sortable('serialize', { key: "sort" }),
            },
            success: function(result) {
                console.log('Orders updated');
            },
            error: function(request,msg,error) {
                console.log('error updating orders');
            }
        });

    }
};

$( function() {
    $("ul.sortList").sortable(sortOptions);
    $("#new, #open, #resolved" ).disableSelection();
} );

$('#user-selector').on('change', function(){
    window.location = '/trello?username='+ $(this).val();
});