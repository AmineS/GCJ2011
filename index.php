<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            $r = new HttpRequest('http://bioniklabs.com/index-5.html', HttpRequest::METH_POST);
            //$r->setOptions(array('cookies' => array('lang' => 'de')));
            //recipient=sometext&required=Name&subject=Contact+Form&redirect=..%2Fthankyou.html&Name=test&Email=test%40gmail.com&Message=test&submit=Send
            $r->addPostFields(array('Name' => 'CG11', 'Email' => 'CG11@gmail','Message'=>'blabla', 'submit'=>'send'));
            //$r->addPostFile('image', 'profile.jpg', 'image/jpeg');
            print_r($r->getPostFields());
            try {
                echo $r->send()->getBody();
            } catch (HttpException $ex) {
                echo $ex;
            }
        ?>
    </body>
</html>
