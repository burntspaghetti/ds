<!DOCTYPE html>
<html>
    <head>
        <meta name="_token" content="{{ csrf_token() }}">
        <title>Diamond Studios</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Find Followers for Github Username </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="search" name="search"></input>
                        </div>
                    </div>
                </div>
            </div>
            <div id="user_info" class="row">

            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div id="thumbnails">

                    </div>
                    <br><br>
                    <script type="text/javascript">

                    </script>
                    <button id="#load_more" onclick="loadMore()" class="btn btn-sm btn-success">Load More...</button>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            $('#search').on('keyup',function(e){
                if(e.keyCode == 13) {//if they press enter, fire off the ajax
                    $value=$(this).val();

                    $.ajax({
                        type : 'get',
                        url : '{{URL::to('search')}}',
                        data:{'search':$value},
                        success:function(data){
                            $('#thumbnails').html(data.row_data);
                            $('#user_info').html(data.user_info);
                            //decide whether or not to show "load more" button
                            if(data.next_page == 1)
                            {
                                //show "load more" button
                                document.getElementById("#load_more").style.display="block";
                            }
                            else if(data.next_page == 0)
                            {
                                //hide "load more" button
                                document.getElementById("#load_more").style.display="none";
                            }
                        }
                    });
                }
            });

            var pageCounter = 1;
            function loadMore() {
                pageCounter++;

                $.ajax({
                    type : 'get',
                    url : '{{URL::to('loadMore')}}',
                    data:{'page_counter':pageCounter},
                    success:function(data){
                        $('#thumbnails').html(data);//do an append?
                        //decide whether or not to show "load more" button
                        if(data.next_page == 1)
                        {
                            //show "load more" button
                            document.getElementById("#load_more").style.display="block";
                        }
                        else if(data.next_page == 0)
                        {
                            //hide "load more" button
                            document.getElementById("#load_more").style.display="none";
                        }
                    }
                });
            }

        </script>
        <script type="text/javascript">
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        </script>
    </body>
</html>