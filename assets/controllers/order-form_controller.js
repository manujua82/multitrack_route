import { Controller } from 'stimulus';

export default class extends Controller {

    connect() {
        var collectionHolder =   $('#order_list');
        collectionHolder.data('index', collectionHolder.find('.panel').length);
        var _addRemoveButton = this.addRemoveButton;
        collectionHolder.find('.panel').each(function () {
           
            // $(this) means the current panel that we are at
            // which means we pass the panel to the addRemoveButton function
            // inside the function we create a footer and remove link and append them to the panel
            // more informations in the function inside
            _addRemoveButton($(this));
        });
    }

    addNewItemForm() {
        var collectionHolder = $('#order_list');
        var prototype =  collectionHolder.data('prototype');
        var index =  collectionHolder.data('index');
        var newForm = prototype;
    
        newForm = newForm.replace(/__name__/g, index);
        collectionHolder.data('index', index+1);

        var panel = $('<tr class="panel"></tr>');

        panel.append(newForm);

        // append the remove button to the new panel
        this.addRemoveButton(panel);
        $('#tbody').append(panel);
    }

    onNewItemClick(e) {
        e.preventDefault();
        this.addNewItemForm();
    }

    addRemoveButton (panel) {
        var removeButton = $(`<a class="phoenix-danger me-2" href="#" data-original-title="Edit" data-toggle="tooltip"><i class="fa-solid fa-trash me-2"></i></a> `);
        // appending the remove button to the panel footer
        var panelFooter = $('<td class="align-middle white-space-nowrap text-center pe-0"></td>').append(removeButton);
        // handle the click event of the remove button
        removeButton.click(function (e) {
            e.preventDefault();
            // gets the parent of the button that we clicked on "the panel" and animates it
            // after the animation is done the element (the panel) is removed from the html
            $(e.target).parents('.panel').slideUp(100, function () {
                $(this).remove();
            })
        });

        // append the footer to the panel
        panel.append(panelFooter);
    }

    onOrderItemChanged(e) {
        console.log(`onOrderItemChanged: ${e.target}`);
    }

}
