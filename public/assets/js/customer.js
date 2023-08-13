// document
//     .querySelector('.add_item_link')
//     .forEach(btn => {
//         console.log('Here');
//         btn.addEventListener("click", addFormToCollection)
//     });

var $collectionHolder;

var $addNewItem = $(`
    <div class="row g-2 ">
        <button class="btn btn-phoenix-secondary me-2"><span class="fa-solid fa-plus me-2"></span><span>Add Address</span></button>
    </div>
`);


$(document).ready(function () {
    
    $collectionHolder = $('#address_list');
    $collectionHolder.append($addNewItem);
    $collectionHolder.data('index', $collectionHolder.find('.panel').length);

    $collectionHolder.find('.panel').each(function () {
        // $(this) means the current panel that we are at
        // which means we pass the panel to the addRemoveButton function
        // inside the function we create a footer and remove link and append them to the panel
        // more informations in the function inside
        addRemoveButton($(this));
    });

    $addNewItem.click(function (e) {
        // preventDefault() is your  homework if you don't know what it is
        // also look up preventPropagation both are usefull
        e.preventDefault();
        // create a new form and append it to the collectionHolder
        // and by form we mean a new panel which contains the form
        addNewForm();
    });
});

const addNewForm = () => {
    console.log(`addNewForm`);

    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index+1);

    var $panel = $('<tr class="panel"></tr>');
    
    $panel.append(newForm);

    // append the remove button to the new panel
    addRemoveButton($panel);

    $tbody = $('#tbody').append($panel);
};

function addRemoveButton ($panel) {
    // create remove button
    
    var $removeButton = $(`<a class="phoenix-danger me-2" href="#" data-original-title="Edit" data-toggle="tooltip"><i class="fa-solid fa-trash me-2"></i></a> `);
    // appending the remove button to the panel footer
    var $panelFooter = $('<td class="align-middle white-space-nowrap text-end pe-0"></td>').append($removeButton);
    // handle the click event of the remove button
    $removeButton.click(function (e) {
        e.preventDefault();
        // gets the parent of the button that we clicked on "the panel" and animates it
        // after the animation is done the element (the panel) is removed from the html
        $(e.target).parents('.panel').slideUp(100, function () {
            $(this).remove();
        })
    });

    console.log($panelFooter);
    // append the footer to the panel
    $panel.append($panelFooter);
}