// document
//     .querySelector('.add_item_link')
//     .forEach(btn => {
//         console.log('Here');
//         btn.addEventListener("click", addFormToCollection)
//     });

var $collectionHolder;

var $addNewItem = $(`
<button class="btn btn-link p-0 ms-3">
    <svg class="svg-inline--fa fa-plus me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
        <path fill="currentColor" d="M432 256c0 17.69-14.33 32.01-32 32.01H256v144c0 17.69-14.33 31.99-32 31.99s-32-14.3-32-31.99v-144H48c-17.67 0-32-14.32-32-32.01s14.33-31.99 32-31.99H192v-144c0-17.69 14.33-32.01 32-32.01s32 14.32 32 32.01v144h144C417.7 224 432 238.3 432 256z"></path>
    </svg>
    <span>Add new</span>
</button>
`);

$(document).ready(function () {
    $header = $('#header_list');
    $header.append($addNewItem);

    $collectionHolder = $('#address_list');
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

    // console.log($panelFooter);
    // append the footer to the panel
    $panel.append($panelFooter);
}