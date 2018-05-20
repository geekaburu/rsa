<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} - RSA Algorithm</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body class="container pb-5">
        <h3 class="text-center my-5">RSA Encryption Asssignment</h3>
        <div class="accordion" id="encryption">
            <div class="card">
                <div class="card-header" id="encrypt">
                    <h5 class="mb-0">
                        <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#encrypt-form" aria-expanded="false" aria-controls="encrypt-form">
                        Encrypt some text
                        </button>
                    </h5>
                 </div>
                 <div id="encrypt-form" class="collapse show" aria-labelledby="encrypt" data-parent="#encryption">
                    <div class="card-body px-1 pr-md-0 pl-md-5">
                        <div class="row justify-content-center">
                            <form class="col-12 col-md-5">
                                <div class="form-group">
                                    <label for="key">Public Key</label>
                                    <input value="" type="text" disabled="" class="form-control" name="key" aria-describedby="tokenHelp" placeholder="Public Key">
                                    <small id="tokenHelp" class="form-text text-muted">This token is private and is used for encryption.</small>
                                </div>
                                <div class="form-group">
                                    <label for="text">Text</label>
                                    <input type="text" class="form-control" name="text" aria-describedby="textHelp" placeholder="Enter the text you would like encrypted">
                                    <small id="textHelp" class="form-text text-muted">This is the text you would like to be encrypted</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Encrypt</button>
                            </form>
                            <div class="col-12 col-md-7">
                                <div class="h-100 row justify-content-center align-items-center">
                                    <div class="col-12 col-md-10">
                                        <div class="card text-white bg-info message">
                                            <div class="card-header">Encryption Results</div>
                                            <div class="card-body" style="overflow-y: auto; height: 200px;">
                                                <p class="card-text">Waiting for results...</p>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="decrypt">
                    <h5 class="mb-0">
                        <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#decrypt-form" aria-expanded="false" aria-controls="decrypt-form">
                        Deencrypt some text
                        </button>
                    </h5>
                 </div>
                 <div id="decrypt-form" class="collapse show" aria-labelledby="decrypt" data-parent="#encryption">
                    <div class="card-body px-1 pr-md-0 pl-md-5">
                        <div class="row justify-content-center">
                            <form class="col-12 col-md-5">
                                <div class="form-group">
                                    <label for="key">Private Key</label>
                                    <input value="" disabled="" type="text" class="form-control" name="key" aria-describedby="tokenHelp" placeholder="Private Key">
                                    <small id="tokenHelp" class="form-text text-muted">This token is private and is used for de-encryption.</small>
                                </div>
                                <div class="form-group">
                                    <label for="text">Text</label>
                                    <input value="" type="text" class="form-control" name="text" aria-describedby="textHelp" placeholder="Enter the text you would like de-encrypted">
                                    <input value="" type="hidden" name="n">
                                    <input value="" type="hidden" name="d">
                                    <small id="textHelp" class="form-text text-muted">This is the text you would like to be de-encrypted</small>
                                </div>
                                <button type="submit" class="btn btn-primary">De-encrypt</button>
                            </form>
                            <div class="col-12 col-md-7">
                                <div class="h-100 row justify-content-center align-items-center">
                                    <div class="col-12 col-md-10">
                                        <div class="card text-white bg-info message">
                                            <div class="card-header">De-encryption Results</div>
                                            <div class="card-body" style="overflow-y: auto; height: 200px;">
                                                <p class="card-text">Copy paste results from encryption box...</p>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>               
                            </div>            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/app.js')}}"></script>
        <script>
            // Handle encryption
            $('#encrypt-form form').on('submit', function(){
                $('#encrypt-form').find('.message .card-text').html('loading.....')
                // Post encryption information
                var url = "{{ route('cypher.encrypt') }}";
                $.post(url,{
                    _token:'{{csrf_token()}}',
                    text: $(this).find('input[name=text]').val(),
                },
                function(data, status){
                    var text = data.encrypted
                    $('#encrypt-form').find('input[name=key]').val(`{${data.e},${data.n}}`)
                    $('#decrypt-form').find('input[name=key]').val(`{${data.d},${data.n}}`)
                    $('#decrypt-form').find('input[name=n]').val(data.n)
                    $('#decrypt-form').find('input[name=d]').val(data.d)
                    $('#decrypt-form').find('input[name=text]').val(text)
                    $('#encrypt-form').find('.message .card-text').html(`
                        <p>The text has been encrypted to <span class="text-warning">${text}</span></p>
                    `)
                    alert(`RSA Encryption Complete. Your Public key is {${data.e},${data.n}} and Private Key is {${data.d},${data.n}}`)
                });                
                return false  
            })
        </script>
        <script>
            // Handle de-encryption
            $('#decrypt-form form').on('submit', function(){
                $('#decrypt-form').find('.message .card-text').html('loading.....')
                // Post de-encryption information
                var url = "{{ route('cypher.decrypt') }}";
                $.post(url,{
                    _token:'{{csrf_token()}}',
                    text: $(this).find('input[name=text]').val(),
                    d: $(this).find('input[name=d]').val(),
                    n: $(this).find('input[name=n]').val(),
                },function(data, status){               
                    var data = data.decrypted
                    $('#decrypt-form').find('.message .card-text').html(`
                        <p>The text has been de-encrypted to <span class="text-warning">${data}</span></p>
                    `)
                });                
                return false  
            })
        </script>
    </body>
</html>