import { Controller } from 'stimulus';
import $ from 'jquery';

export default class extends Controller { 
    connect() { 
        $('#config_delete').val(false);
        $('#deleteImage').on('click', function (e) {
            e.preventDefault();
            $('#previewImage').fadeOut('fast');
            $('#imageUpload').fadeIn('slow');
            $('#config_delete').val(true);
        })
    }
}