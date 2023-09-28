import { Controller } from 'stimulus';

export default class extends Controller {

    form = null;

    connect() {
        var _addRemoveButton = this.addRemoveButton;
        var collectionHolder =   $('#order_list');
        collectionHolder.data('index', collectionHolder.find('.panel').length);
        collectionHolder.find('.panel').each(function () {
            _addRemoveButton($(this));
        });

        const orderTypeSelect = document.getElementById("order_type");
        orderTypeSelect.addEventListener('change', this.onOrderTypeChanged);
    }

    onOrderTypeChanged(e) {
        e.preventDefault();
        const pickupDiv =  document.getElementById("pickup_section");
        const customerLabel =  document.getElementById("customer_label");
        const deliveryLabel =  document.getElementById("delivery_label");

        if(e.target.value == "Pickup & Delivery"){
            customerLabel.style.display = "none";
            deliveryLabel.style.display = "block";
            pickupDiv.style.display = "block";
        } else {
            customerLabel.style.display = "block";
            deliveryLabel.style.display = "none";
            pickupDiv.style.display = "none";
        }
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

        this.addChangeListenerToItem(index);
    }

    addChangeListenerToItem(index) {
        this.form = document.getElementById('order_form');
        
        const orderItem = document.getElementById(`order_orderItems_${index}_item_autocomplete`);
        const unitMeasure = document.getElementById(`order_orderItems_${index}_unitMeasure`);
        const qtyInput = document.getElementById(`order_orderItems_${index}_qty`);
        const priceInput = document.getElementById(`order_orderItems_${index}_price`);
        const amountInput = document.getElementById(`order_orderItems_${index}_totalAmount`);
        
        orderItem.addEventListener('change', async (e) => {
            let requestBody = e.target.getAttribute('name') + '=' + e.target.value;
            const updateFormResponse = await updateForm(requestBody, form.getAttribute('action'), form.getAttribute('method'));
            const html = this.parseTextToHtml(updateFormResponse);

            const newUnitMeasure = html.getElementById(`order_orderItems_${index}_unitMeasure`);
            unitMeasure.value = newUnitMeasure.value;

            const newQtyInput = html.getElementById(`order_orderItems_${index}_qty`);
            qtyInput.value = newQtyInput.value;

            const newPriceInput = html.getElementById(`order_orderItems_${index}_price`);
            priceInput.value = newPriceInput.value;

            const newAmountInput = html.getElementById(`order_orderItems_${index}_totalAmount`);
            amountInput.value = newAmountInput.value;
        });

        qtyInput.addEventListener('change', async (e) => {
            console.log(`onQTYChanged`);
            amountInput.value = this.calculateItemTotalAmount(Number(e.target.value), Number(priceInput.value));
        });
        priceInput.addEventListener('change', async(e) => {
            console.log(`onPriceChanged`);
            amountInput.value = this.calculateItemTotalAmount(Number(qtyInput.value), Number(e.target.value))
        });
    }

    calculateItemTotalAmount(qty, price) {
        if (qty <= 0 ||price <=0){
            return 0
        }
       return (qty * price);
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

    parseTextToHtml(text){
        const parser = new DOMParser();
        const html = parser.parseFromString(text, 'text/html');
        return html;
    };

    async updateForm(data, url, method) {
        const req = await fetch(url, {
            method: method,
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'charset': 'utf-8'
            }
        });
        const text = await req.text();
        return text;
    };

}
