import { Controller } from 'stimulus';
import $ from 'jquery';

export default class extends Controller {

    form = null;

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

    connect() {
        this.form = document.getElementById('order_form');
            
        const orderTypeSelect = document.getElementById("order_type");
        orderTypeSelect.addEventListener('change', (e) => this.onOrderTypeChanged(e));
        this.updateFormByType(orderTypeSelect.value);

        const form_select_customer = document.getElementById('order_customerId_autocomplete');
        form_select_customer.addEventListener('change', (e) => this.onCustomerSelected(e));

        const customer_pickup = document.getElementById('order_pickupCustomerId_autocomplete');
        customer_pickup.addEventListener('change', (e) => this.onPickupCustomerSelected(e));

        var collectionHolder = $('#order_list');
        collectionHolder.data('index', collectionHolder.find('.panel').length);
        collectionHolder.find('.panel').each((index, element) => {
            this.addChangeListenerToItem(index);
            this.addRemoveButton($(element));
        });
    }

    updateFormByType(type) {
        const pickupDiv =  document.getElementById("pickup_section");
        const customerLabel =  document.getElementById("customer_label");
        const deliveryLabel =  document.getElementById("delivery_label");

        if(type== "Pickup & Delivery"){
            customerLabel.style.display = "none";
            deliveryLabel.style.display = "block";
            pickupDiv.style.display = "block";
        } else {
            customerLabel.style.display = "block";
            deliveryLabel.style.display = "none";
            pickupDiv.style.display = "none";
        }
    }

    onOrderTypeChanged(e) {
        e.preventDefault(e);
        this.updateFormByType(e.target.value)
    }

    async onCustomerSelected(e) {
            let requestBody = e.target.getAttribute('name') + '=' + e.target.value;

            const form_select_address = document.getElementById('order_addressId');
            const updateFormResponse = await this.updateForm(requestBody, this.form.getAttribute('action'), this.form.getAttribute('method'));
            
            const html = this.parseTextToHtml(updateFormResponse);
            const new_form_select_address = html.getElementById('order_addressId');
            form_select_address.innerHTML = new_form_select_address.innerHTML;

            const form_contact_name = document.getElementById('order_contactName');
            form_contact_name.value = html.getElementById('order_contactName').value;

            const form_contact_phone = document.getElementById('order_customerPhone');
            form_contact_phone .value = html.getElementById('order_customerPhone').value;

            const form_contact_email = document.getElementById('order_customerEmail');
            form_contact_email.value = html.getElementById('order_customerEmail').value;

            // const form_time_from = document.getElementById('order_timeFrom');
            // form_time_from.value = html.getElementById('order_timeFrom').value;

            // const form_time_until = document.getElementById('order_timeUntil');
            // form_time_until.value = html.getElementById('order_timeUntil').value;
    }

    async onPickupCustomerSelected(e) {
        let requestBody = e.target.getAttribute('name') + '=' + e.target.value;

        const updateFormResponse = await this.updateForm(requestBody, this.form.getAttribute('action'), this.form.getAttribute('method'));
       
        const html = this.parseTextToHtml(updateFormResponse);
        const form_select_address = document.getElementById('order_pickupAddressId');
        const new_form_select_address = html.getElementById('order_pickupAddressId');
        form_select_address.innerHTML = new_form_select_address.innerHTML;

        const form_contact_name = document.getElementById('order_pickupContactName');
        form_contact_name.value = html.getElementById('order_pickupContactName').value;

        const form_contact_phone = document.getElementById('order_pickupCustomerPhone');
        form_contact_phone .value = html.getElementById('order_pickupCustomerPhone').value;

        const form_contact_email = document.getElementById('order_pickupCustomerEmail');
        form_contact_email.value = html.getElementById('order_pickupCustomerEmail').value;

        // const form_time_from = document.getElementById('order_timeFrom');
        // form_time_from.value = html.getElementById('order_timeFrom').value;

        // const form_time_until = document.getElementById('order_timeUntil');
        // form_time_until.value = html.getElementById('order_timeUntil').value;
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
            const updateFormResponse = await this.updateForm(requestBody, this.form.getAttribute('action'), this.form.getAttribute('method'));
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
            amountInput.value = this.calculateItemTotalAmount(Number(e.target.value), Number(priceInput.value));
        });
        priceInput.addEventListener('change', async(e) => {
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
        removeButton.on("click", function (e) {
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
}
