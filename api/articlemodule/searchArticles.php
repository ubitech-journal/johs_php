<?php

header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');

class searchArticles
{
  public function strip_word_html($text, $allowed_tags = '<b><i><sup><sub><em><strong><u><br>') 
    { 
      mb_regex_encoding('UTF-8'); 
        //replace MS special characters first 
        $search = array( '/HREF=&quot;/u','/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u', '/&quot;/u', '/&acute;/u', '/&ndash;/u', '/&acirc;/u', '/&frasl;/u', '/&reg;/u', '/&uacute;/u', '/&eacute;/u', '/&acute;/u', '/&acirc;/u', '/&oacute;/u', '/&beta;/u', '/&alpha;/u', '/&atilde;/u', '/&mu;/u', '/&pi;/u', '/&Omega;/u', '/&deg;/u', '/&infin;/u', '/&trade;/u', '/&plusmn;/u', '/&auml;/u', '/&euml;/u', '/&uuml;/u', '/&yuml;/u'); 
        $replace = array('HREF="','\'', '\'', '\"', '\"', '\-', '\"', "\'", '\—', '\ ', '\/', '\®', '\ú', '\é', '\´', '\â', '\ó', '\ß', '\a', '\ã', '\µ', '\p', '\?', '\º', '\8', '\™', '\±', '\ä', '\ë', '\ü', '\ÿ'); 
        $text = preg_replace($search, $replace, $text); 
        //make sure _all_ html entities are converted to the plain ascii equivalents - it appears 
        //in some MS headers, some html entities are encoded and some aren't 
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8'); 
        //try to strip out any C style comments first, since these, embedded in html comments, seem to 
        //prevent strip_tags from removing html comments (MS Word introduced combination) 
        if(mb_stripos($text, '/*') !== FALSE){ 
            $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm'); 
        } 
        //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be 
        //'<1' becomes '< 1'(note: somewhat application specific) 
        $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text); 
        $text = strip_tags($text, $allowed_tags); 
        //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one 
        $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text); 
        //strip out inline css and simplify style tags 
        $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu'); 
        $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>'); 
        $text = preg_replace($search, $replace, $text); 
        //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears 
        //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains 
        //some MS Style Definitions - this last bit gets rid of any leftover comments */ 
        $num_matches = preg_match_all("/\<!--/u", $text, $matches); 
        if($num_matches){ 
              $text = preg_replace('/\<!--(.)*--\>/isu', '', $text); 
        } 
        return $text; 
    }
  
    public static function encrypt($string, $key="ubitech")
  {
      $result = '';
      for($i=0; $i<strlen($string); $i++)
      {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
      }
      return base64_encode($result);
  }

    public function newsAnnouncements() {
      $data = array();
      $sql = "select * from news_master where news_sts = 'Visible'";
      $dbq = query($sql);

      header('Content-type: text/javascript');
    while ($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("newsAnnouncements"=>$data))); 
    }

    public function getTrending_articles($org_id) {
      $data = array();
    $sql = "SELECT * FROM articles where  DATE(publish_date) BETWEEN DATE_SUB((SELECT max(publish_date) FROM `articles`), INTERVAL 30 DAY) AND NOW() AND org_id = $org_id ORDER BY view desc LIMIT 10";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while ($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("getTrending_articles"=>$data))); 
    }

    public function getTrending_articlesApi($org_id) {
      $data = array();
    $sql = "SELECT * FROM articles where  DATE(publish_date) BETWEEN DATE_SUB((SELECT max(publish_date) FROM `articles`), INTERVAL 30 DAY) AND NOW() AND org_id = $org_id ORDER BY view desc LIMIT 20";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while ($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("getTrending_articlesApi"=>$data))); 
    }

    public function getMostViewedArticleApi($org_id)
    {
    $data = array();
    $sql = "select a.*,a.file_url as filelink,b.issue_no, c.category_name from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and a.org_id = $org_id order by view desc limit 20";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while ($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("getMostViewedArticleApi"=>$data)));
  }

    public function getMostViewArticleApi($org_id)
  {
    $data = array();
    // $sql = "select article_id, title, authors, cover_page  from articles a, issue_master b where a.issue_id = b.issue_id order by view desc limit 10";
    $sql = "select article_id, title, authors, fileimage  from articles a, issue_master b where a.issue_id = b.issue_id and a.org_id = $org_id order by view desc limit 10";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while ($result = fetch_assoc($dbq)) {
      array_push($data, array("article_id" => $result['article_id'], "title" => html_entity_decode($result['title']), "authors" => html_entity_decode($result['authors']), "fileimage" => $result['fileimage']));
    }
    print_r(json_encode(array("getMostViewArticleApi"=>$data)));
  }

    public function commentArticle($data) {
      $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
      $name = $data->name;
      $email = $data->email;
      $comment = $data->comment;
      $sql = "select * from comment_article where email = '$email' and article_id = $id and sts = 0";
      $dbq = query($sql);

      if(getRowCount($dbq) >= 1){
        print_r(0);
      }else{
      $sql = "select * from comment_article where email = '$email' and article_id = $id and sts = 1";
      $dbq = query($sql);

      if(getRowCount($dbq) >= 1){
          print_r(0);
      }else{
        $date = date('Y-m-d');
        $sqll = "insert into comment_article(name, email, comment, article_id) values ('$name','$email','$comment', $id)";
        $dbq = query($sqll);
        print_r($dbq);
      }
      }
    }

    public function commentArticleFetch() {
      $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
      $data = array();
      $sql = "select * from comment_article where article_id = $id and sts = 1";
      $dbq = query($sql);
      header('Content-type: text/javascript');
      if(getRowCount($dbq) >= 1){
        while($result = fetch_assoc($dbq)) {
          array_push($data, $result);
        }
      }
      print_r(json_encode($data));
    }

   public function getEditorContents($id, $org_id)
  {
    // $sql = "select contents from editor_master where editor_id=$id";
    $sql = "select page_content from page_master where page_id = $id and page_sts = 1 and org_id = $org_id";
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
      while($records = fetch($dbq))
      { 
        echo json_encode(array("authorsGuidelines"=>html_entity_decode($records['page_content'])));
        break;
      }
    }
  }
   public function privacy_polices($id, $org_id)
  {
    // $sql = "select contents from editor_master where editor_id=$id";
    $sql = "select page_content from page_master where page_id = $id and page_sts = 1 and org_id = $org_id";
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
      while($records = fetch($dbq))
      { 
        echo json_encode(array("authorsGuidelines"=>html_entity_decode($records['page_content'])));
        break;
      }
    }
  }

     public function trem_services($id, $org_id)
  {
    // $sql = "select contents from editor_master where editor_id=$id";
    $sql = "select page_content from page_master where page_id = $id and page_sts = 1 and org_id = $org_id";
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
      while($records = fetch($dbq))
      { 
        echo json_encode(array("authorsGuidelines"=>html_entity_decode($records['page_content'])));
        break;
      }
    }
  }
  public function submit_menuscript()
  {
    $sql = "select contents from submit_menuscript where submit_id=1";
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
    while($records = fetch($dbq))
    { 
      echo json_encode(array("submit_menuscript"=>$records['contents']));
      break;
      }
    }
  }

  public function authorAffiliation() {
    $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
    $sql = "SELECT description FROM articles a join issue_master i where a.issue_id=i.issue_id and article_id = ".$id." order by cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $data = array();
    $dbq = query($sql);
      header('Content-type: text/javascript');
    while($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("authorAffiliation"=>$data)));
  }

  public function get_articles($author){
    $dataa = json_decode($author);
    $cat1_total = 0;
    $data = array();
    $author = preg_replace("/<sup>(.*?)<\/sup>/i","",$dataa->author);
    $author =   strip_tags($author);
    $author =   trim($author);
      $sql = "select * from articles where authors LIKE '%".$author."%' ";
      $dbq = query($sql);
      header('Content-type: text/javascript');
    while($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("get_articles"=>$data)));
  }

    public function relatedSearch(){
      $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
      $authorss = array();
      $data = array();
      $sql = "SELECT * FROM articles a join issue_master i where a.issue_id=i.issue_id and article_id = $id order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
      $dbq = query ($sql);
      header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {     
      while($records = fetch($dbq))
      { 
        $authors = explode(", ",$records['authors']);
        foreach ($authors as $value) {
        array_push($authorss, strip_tags(trim(preg_replace('|<sup>(.*?)</sup>|', '', html_entity_decode($value)))));
        }
      }
    }
    foreach ($authorss as $value) {
    $var=explode(" ", $value);
      $authorsRelated = array();
      $count=count($var);
      $a = array();
      if($count==1){
        $values=$var[0];
        array_push($a, $values);
      }
      else if($count==2){
        $values=$var[1]." ".substr($var[0], 0, 1);
        array_push($a, $values);
      }
     else if($count==3){
        $values=$var[2]." ".substr($var[0], 0, 1)." ".substr($var[1], 0, 1);
        array_push($a, $values);
      }
        else if($count==4){
      $values=$var[3]." ".substr($var[0], 0, 1)." ".substr($var[1], 0, 1)." ".substr($var[2], 0, 1);
      array_push($a, $values);
        }
      else{
      $values=$var[4]." ".substr($var[0], 0, 1)." ".substr($var[1], 0, 1)." ".substr($var[2], 0, 1)." ".substr($var[3], 0, 1);
      array_push($a, $values);
      }
      array_push($data, $a[0]);
    }
    print_r(json_encode(array("relatedSearch"=>$data)));
    }

    public function similarArticle() {
    $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
    $articleId = array();
    $finalArticle = array();
    $sql = "SELECT * FROM articles a join issue_master i where a.issue_id=i.issue_id and article_id = $id order by cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbq = query($sql);
    if(getRowCount($dbq) >= 1){
    while($result = fetch($dbq)){
    $keywords = self::strip_word_html(html_entity_decode($result['keywords'],ENT_COMPAT, "UTF-8"), $allowed_tags = '<b><i><sub><em><strong><u><br><p> <a><ul><ol><li><br>');
      $keywords = explode(', ', $keywords);
      foreach($keywords as $value){
        $sql = "select * from articles where keywords LIKE '%".$value."%' and article_id<>'".$result['article_id']."'";
        $dbq = query($sql);
        while($results = fetch($dbq)){

          array_push($articleId, $results['article_id']);
        }
      }
    }
    }else{
    $sql = "SELECT * FROM articles where article_id = $id order by cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbq = query($sql);
    if(getRowCount($dbq) >= 1){
    while($result = fetch($dbq)){
    $keywords = self::strip_word_html(html_entity_decode($result['keywords'],ENT_COMPAT, "UTF-8"), $allowed_tags = '<b><i><sub><em><strong><u><br><p> <a><ul><ol><li><br>');
      $keywords = explode(', ', $keywords);
      foreach($keywords as $value){
        $sql = "select * from articles where keywords LIKE '%".$value."%' and article_id<>'".$result['article_id']."'";
        $dbq = query($sql);
        while($results = fetch($dbq)){
          array_push($articleId, $results['article_id']);
        }
       }
      }
       }
    }
    $articleId = array_unique($articleId);
    foreach ($articleId as $value) {
      $sql = "select * from Articles where Article_id = $value";
      $dbq = query($sql);
        while($results = fetch($dbq)){
          array_push($finalArticle, array("id"=>$results['article_id'],"title"=>$results['title'],"authors"=>$results['authors']));
        }
    }
    print_r(json_encode(array("similarArticle"=>$finalArticle)));
    }

    public function updateCountView($org_id){

      $id = $_REQUEST['countview_id'];
    $sql1 = "select view from articles where article_id=".$id." and org_id = ".$org_id."";
    $dbq1 = query ($sql1);  
    if( getRowCount($dbq1) >=1)
    {
      while($records1 = fetch($dbq1))
      { 
        $count1=$records1['view']+1;
        $sql2="update articles set view=".$count1." where article_id=".$id." and org_id = ".$org_id."";
        $res = query ($sql2);
        break;
      }
    }
    echo $res;
    }

    public function updateCountDownload($org_id){

      $id = $_REQUEST['countdownload_id'];
    $sql1 = "select download from articles where article_id=".$id." and org_id = ".$org_id."";
    $dbq1 = query ($sql1);  
    if( getRowCount($dbq1) >=1)
    {
      while($records1 = fetch($dbq1))
      { 
        $count1=$records1['download']+1;
        $sql2="update articles set download=".$count1." where article_id=".$id." and org_id = ".$org_id."";
        $res = query ($sql2);
        break;
      }
    }
    echo $res;
    }

    public function getTotalMetrics(){
    $article_id1 = $_REQUEST['article_id'];
      $sql5 = "select download,view  from articles where  article_id = '".$article_id1."' ";
    $dbq55 = query ($sql5);
    $data3 = array();   
    $res1 = array();    
    if($dbq55)
    {
      while($row5 = fetch($dbq55)) 
      {
           $data3['download'] = $row5['download'];
         $data3['view']  = $row5['view'];
         $viewo      =      $data3['view'];
         $Downloado =  $data3['download'];
           $data3['total']  = $data3['view'] +$data3['download'];
         $res3[]            = $data3;
       }
    }
     echo $data3 = json_encode($res3);
    }

    public function getArticleMetrics(){
    $article_id1 = $_REQUEST['article_id'];
    for($l=0; $l<$article_id1; $l++){
      $sql5 = "select Dated,Download,View  from ViewDownload where  Article_id = '".$article_id1."' order by Dated asc";
    $dbq55 = query ($sql5);
    $i=0;
    $data1 = array();
    $res1 = array();
    if($dbq55)
    {
      while($row5 = fetch($dbq55))
      {
         $time1             = $row5['Dated'];
         $Download          = $row5['Download'];
         $data1['monthName']= date("F",strtotime($time1));
         $data1['yearname'] = date("Y",strtotime($time1));
         $data1['Download'] = $row5['Download'];
         $data1['View']     = $row5['View'];
           $data1['View']     = $row5['View'];
         $view1             = $data1['View'];
         $Download          = $data1['Download'];
         $total             = $view1+$Download;
         $res1[]            = $data1;
       }
    }
    }
    echo  $data1 = json_encode($res1);
    }

    public function getAbstractDatavalue() {
    
    $id   = isset ($_REQUEST['id']) ? $_REQUEST['id'] : '';
    
    $data=array();
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    $sql = "SELECT * FROM articles a, issue_master i, category_master c WHERE a.issue_id = i.issue_id and a.article_category = c.category_id and article_id=$id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    if( getRowCount($query) >=1)
    {
    header('Content-type: text/javascript');
    $query = fetch_assoc($query);
    array_push($data,$query);
    print_r(json_encode(array("getAbstractDatavalue"=>$data)));
    }else{
      $sql = "SELECT * FROM articles a, category_master c WHERE a.article_category = c.category_id and article_id=$id ORDER BY article_id LIMIT 1";
      $query = query($sql);
      if( getRowCount($query) >=1)
      {
        header('Content-type: text/javascript');
        $query = fetch_assoc($query);
        array_push($data,$query);
        
        print_r(json_encode(array("getAbstractDatavalue"=>$data)));
      }else{
        print_r(json_encode(array("getAbstractDatavalue"=>"Not Found")));
      }
    }
  }


  public function abstractMeta() {
    $data=array();
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    $sql = "SELECT * FROM articles a, issue_master i, category_master c WHERE a.issue_id = i.issue_id and a.article_category = c.category_id and article_id=$article_id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    if( getRowCount($query) >=1)
    {
      while($results = fetch_assoc($query)){
      array_push($data, array("name"=>"dc.identifier","content"=>strip_tags(html_entity_decode($results['doi']))));
      array_push($data, array("name"=>"citation_title","content"=>strip_tags(html_entity_decode($results['title']))));
      $authors = explode(', ', $results['authors']);
      foreach($authors as $result){
      array_push($data, array("name"=>"citation_author","content"=>strip_tags(trim(preg_replace('|<sup>(.*?)</sup>|', '', html_entity_decode($result))))));
      }
      $issue_no = explode(", ",$results['issue_no']);
      $pages = explode("-",$results['pages']);
      array_push($data, 
      array("name"=>"citation_publication_date","content"=>$results['publish_date']),
      array("name"=>"citation_journal_title","content"=>"JOURNAL OF HEALTHCARE SCIENCES"),
      array("name"=>"citation_volume","content"=>filter_var($issue_no[0], FILTER_SANITIZE_NUMBER_INT)),
      array("name"=>"citation_issue","content"=>filter_var($issue_no[1], FILTER_SANITIZE_NUMBER_INT)),
      array("name"=>"citation_firstpage","content"=>$pages[0]),
      array("name"=>"citation_lastpage","content"=>$pages[1]),
      array("name"=>"citation_pdf_url","content"=>urldecode($_SERVER['SERVER_NAME'].'%2Fadmin%2Fpublic%2Fuploads%2F187%2F'.$results['file_url']))
      );        
      }
      print_r(json_encode(str_replace("\\", "", $data)));
    }
  }

  public function abstractHeader() {

    $data=array();
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    $sql = "SELECT * FROM articles a, issue_master i, category_master c WHERE a.issue_id = i.issue_id and a.article_category = c.category_id and article_id=$article_id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    if( getRowCount($query) >=1)
    {
    header('Content-type: text/javascript');
    $query = fetch_assoc($query);
    $queryy = html_entity_decode($query['authors']);
    $link = 'admin/php/uploads/'.$query['file_url'];
    $size=filesize($link);
    $byte=number_format($size/1048576,1);
    array_push($data,["fileSize"=>$byte],$query, ["Authors"=>$queryy]);
    print_r(json_encode(array("abstractHeader"=>$data)));
    }else{
      print_r(json_encode(array("abstractHeader"=>"Not Found")));
    }
  }

  public function checkedjabrecord($email)
  {
    $sts =false;
    $date= date("Y-m-d H:i:s");
    $sql = "select * from subscribe_master where email_id='$email'";
    $dbq = query ($sql);
    if(getRowCount($dbq)==0)
    {
      $sts =1;
    }else{
      $sts =0;
    }
      return $sts;
  }
  
  public function insertjabrecord($email)
  {
    $sts =false;
    $date= date("Y-m-d H:i:s");
    $sql = "select * from subscribe_master where email_id='$email'";
    $dbq = query ($sql);
    if(getRowCount($dbq)==0)
    {
      $sql = "insert into subscribe_master (email_id,sub_date) values('$email','$date')";
      $dbq = query ($sql);
      $sts =true;
    }else{
      $sts =false;
    }
      return $sts;
  }

  public function arcIssueArticleDetails($org_id)
  {
    $cat1_total = 0;
    $data=array();
    $issueid  = isset ($_REQUEST['issueid']) ? $_REQUEST['issueid'] : '';   

    $sql = "select c.category_name, b.issue_no, b.issue_period  from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_id = ".$issueid." and b.issue_status='Visible' and a.org_id = $org_id group by c.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbq = query($sql);
    $dbqq = fetch($dbq);
    array_push($data, $dbqq[1],$dbqq[2]);
    foreach ($dbq as $value) {
    $sqll = "select count(c.category_name) as count,c.category_name from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_id = ".$issueid." and b.issue_status='Visible' and c.category_name = '".$value['category_name']."' and a.org_id = $org_id order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbqq = query($sqll);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbqq)) {
      array_push($data, $row);
      }
    }
    print_r(json_encode(array("arcIssueArticleDetails"=>$data)));            
  }

  public function arcIssueArticle($org_id)
  {
    $cat1_total = 0;
    $data = array();
    $issueid  = isset ($_REQUEST['issueid']) ? $_REQUEST['issueid'] : '';   
    $sql = "select a.*,a.file_url as filelink,c.category_name as subject, b.issue_no, b.issue_period, t.category_name from articles a, issue_master b, category_master c,article_type t where a.article_category = c.category_id and a.article_type = t.category_id and a.issue_id = b.issue_id and b.issue_status='Archive' and a.issue_id = ".$issueid." and a.org_id = ".$org_id." order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    
    $dbq = query($sql);
    
    if( getRowCount($dbq) >=1)
    {
      
    header('Content-type: text/javascript');
    while($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
      print_r(json_encode(array("arcIssueArticle"=>$data)));  

    } else{

      print_r(json_encode(array("arcIssueArticle"=>"Not Found Article"))); 
    }          
  }

  // public function arcIssueArticleSubject($org_id)
  // {
  //   $cat1_total = 0;
  //   $data = array();
  //   $issueid  = isset ($_REQUEST['issueid']) ? $_REQUEST['issueid'] : '';   
  //   $sql = "select t.category_name ,c.category_name as subject from articles a, issue_master b, category_master c, article_type t  where a.article_category = c.category_id and t.category_id = a.article_type and a.issue_id = b.issue_id and b.issue_status='Archive' and a.issue_id = ".$issueid."  and a.org_id = ".$org_id." group by c.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
  //   $dbq = query($sql);
  //   header('Content-type: text/javascript');
  //   while($result = fetch_assoc($dbq)) {
  //     array_push($data, $result);
  //   }
  //   print_r(json_encode(array("arcIssueArticleSubject"=>$data)));              
  // }

  public function arcIssueArticleSubject($org_id)
  {
    $cat1_total = 0;
    $data = array();
    $issueid  = isset ($_REQUEST['issueid']) ? $_REQUEST['issueid'] : '';
    $sql = "select t.category_name, b.issue_period from articles a, issue_master b, article_type t  where t.category_id = a.article_type and a.issue_id = b.issue_id and b.issue_status='Archive' and a.issue_id = ".$issueid."  and a.org_id = ".$org_id." group by t.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while($result = fetch_assoc($dbq)) {
      array_push($data, $result);
    }
    print_r(json_encode(array("arcIssueArticleSubject"=>$data)));
  }


  public function pastIssueStatus($org_id)
  {

    $data=array();
    $issueid  = isset ($_REQUEST['issueid']) ? $_REQUEST['issueid'] : '';
    $sqll = "select b.issue_no, b.issue_period from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_status='Archive' and a.issue_id = $issueid and b.org_id = $org_id group by c.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbqq = query($sqll);
    $dbqq = fetch($dbqq);
    array_push($data, $dbqq[0],$dbqq[1]);
    print_r(json_encode(array("pastIssueStatus"=>$data)));
  }

  public function onlinefirstArticleDetails()
  {
    $cat1_total = 0;
    $data=array();
    $sqll = "select count(b.category_name) as count, b.category_name from articles a, category_master b where a.issue_id = '0' and a.article_category = b.category_id and b.category_name IN (select b.category_name from articles a, category_master b where a.issue_id = '0' and a.article_category = b.category_id group by b.category_name) group by b.category_name";
    $dbqq = query($sqll);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbqq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("onlinefirstArticleDetails"=>$data)));
  }

  public function onlinefirstArticleSubject()
  {
    $cat1_total = 0;
    $data=array();
    $sql = "select count(b.category_name) as count, b.category_name from articles a, category_master b where a.issue_id = '0' and a.article_category = b.category_id group by b.category_name";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("onlinefirstArticleSubject"=>$data)));
  }

  public function onlinefirstArticle()
  {
    $cat1_total = 0;
    $data=array();
    $sql = "select a.*, b.category_name from articles a, category_master b where a.issue_id = '0' and a.article_category = b.category_id";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("onlinefirstArticle"=>$data)));
  }

    public function showArchivesYear($org_id)
  {
    $sql = "SELECT issue_period as year FROM issue_master where issue_status='Archive' and org_id = $org_id order by issue_id desc";
    $data = array();
    $year = array();
    $dbq = query ($sql);
    if( getRowCount($dbq) >=1)
    {
      while($row = fetch_assoc($dbq)){
        if(substr_count($row['year'], ',')){
          $rows = explode(',', $row['year']);
        }else{
          $rows = explode(' ', $row['year']);
        }
        array_push($data, trim($rows[1]));
      }
      $year = array_unique($data);
    }

    foreach ($year as $value) {
      array_push($year, $value);
    }
    print_r(json_encode(array("showArchivesYear"=>array_values(array_filter(array_unique($year))))));
  }
  
  public function showArchives($org_id)
  {
    $sql = "SELECT * FROM issue_master where issue_status='Archive' and org_id = $org_id order by issue_id";
    $data = array();
    $dbq = query ($sql);
    if( getRowCount($dbq) >=1)
    {
      while($row = fetch_assoc($dbq)){
        array_push($data, $row);
      }
      print_r(json_encode(array("showArchives"=>$data)));
    }
  }

  public function currentIssueArticleDetails($org_id)
  {
      $cat1_total = 0;
      $data=array();
      $sql = "select c.category_name, b.issue_no, b.issue_period  from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_status='Current' and b.org_id = $org_id group by c.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbq = query($sql);
    $dbqq = fetch($dbq);
    array_push($data, $dbqq[1],$dbqq[2]);
    foreach ($dbq as $value) {
    $sqll = "select count(c.category_name) as count,c.category_name from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_status='Current' and c.category_name = '".$value['category_name']."' and b.org_id = $org_id order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbqq = query($sqll);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbqq)) {
      array_push($data, $row);
    }
    }
    print_r(json_encode(array("currentIssueArticleDetails"=>$data)));
  }

  public function currentIssueStatus($org_id)
  {

  $data=array();
    $sqll = "select b.issue_no, b.issue_period from articles a, issue_master b, category_master c where a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_status='Current' and b.org_id = $org_id group by c.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbqq = query($sqll);
    $dbqq = fetch($dbqq);
    array_push($data, $dbqq[0],$dbqq[1]);
    print_r(json_encode(array("currentIssueStatus"=>$data)));
  }

  public function currentIssueArticleSubject($org_id)
  {
    $cat1_total = 0;
    $data=array();
    $sql = "select t.category_name from articles a, issue_master b, article_type t where t.category_id = a.article_type and a.issue_id = b.issue_id and b.issue_status='Current' and b.org_id = $org_id group by t.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)";
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("currentIssueArticleSubject"=>$data)));
  }

    public function currentIssueArticletype($org_id)
  {
    $cat1_total = 0;
    $data=array();
    $sql = "select t.category_name,c.category_name as subject from articles a,category_master c, issue_master b,article_type t where a.article_type = t.category_id and a.article_category = c.category_id and  a.issue_id = b.issue_id and b.issue_status='Current' and b.org_id = $org_id group by t.category_name order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned) " ;
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("currentIssueArticletype"=>$data)));
  }

  public function currentIssueArticle($org_id)
  {
    $cat1_total = 0;
    $data=array();
    $sql = "select a.*,a.file_url as filelink,a.material_pdf,c.category_name as subject,b.issue_no, t.category_name  from articles a,category_master c, issue_master b, article_type t where a.article_type = t.category_id and a.article_category = c.category_id and a.issue_id = b.issue_id and b.issue_status='Current' and b.org_id = $org_id order by  cast(trim(SUBSTRING_INDEX(pages,'-',1)) as unsigned)" ;
    $dbq = query($sql);
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("currentIssueArticle"=>$data)));
  }

  public function getCurrentIssueTitle($org_id)
  {
    $sql = "select * from issue_master where issue_status='Current' and b.org_id = $org_id";
    $dbq = query ($sql);
    assert ($dbq);
    $i=1;
    if( getRowCount($dbq) >=1)
    {
      print_r(json_encode(array("getCurrentIssueTitle"=>fetch_assoc($dbq))));
    }
  }

  public  function getIssueNo($issueid)
  {
    $issue_no = "";
    $sql = "select issue_no from issue_master where issue_id=$issueid";
    $dbq = query ($sql);
    assert ($dbq);
    if( getRowCount($dbq) >=1)
    {
      $records = fetch($dbq);
      $issue_no = trim($records['issue_no']);
    }
    return $issue_no;
  }

  public function saveBibtextFile()
  {
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    $sql = "SELECT * FROM articles WHERE article_id=$article_id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    
    $abst ='';
    
    $volume='';
    $number='';
    $issue_id='';
    $reference='';
    $doi='';
    $authors='';
    $title='';
    $pages='';
    $reference='';
    $publish_date='';
    $keywords=''; $abst=""; $doiurl="";
    if(getRowCount($query)>=1)
    {
      while($row = fetch($query))
      {
        $article_id=$row['article_id'];
        $file_url=$row['file_url'];
        $issue_id = $row['issue_id'];
        $reference=$row['reference'];
        $doi=$row['doi'];
        $authors=strip_tags(html_entity_decode($row['authors']));
        $title=strip_tags(html_entity_decode($row['title']));
        $pages=$row['pages'];
        $keywords=strip_tags(html_entity_decode($row['keywords']));
        $abst=strip_tags(html_entity_decode($row['long_desc']));
        $doiurl=$row['doiurl'];
        $reference=$row['reference'];
        $publish_date=$row['publish_date'];
        $number=$row['number'];
        $volume=$row['volume'];
        
        
      }
    }
    $issue_no=$this->getIssueNo($issue_id);
    $year=($publish_date!="")?date("Y",strtotime($publish_date)):date("Y");
    $articleno="johs".$article_id;

    $content = "@article{".$articleno.",
author = {".$authors."},
title = {".$title."},
journal = {Journal of Healthcare Sciences},
volume = {".$volume."},
number = {".$number."},
Issue = {".$issue_no."},
year = {".$year."},
keywords = {".$keywords."},
abstract = {".$abst."},
issn = {},
url = {http://johs.com.sa/#/pages/issue/abstract/".$article_id."},
doi = {".$doi."};
pages = {".$pages."}}"; 
    $fp = fopen("./bib_files/$articleno.bib","w");
    fwrite($fp,$content);
    fclose($fp);
    
    $filename1 = $issue_no."_".$article_id.".xml";
    $filename=str_replace(' ','',$filename1);
  }

  public function saveEndNotetextFile()
  {
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    $sql = "SELECT * FROM articles WHERE article_id=$article_id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    
    $issue_id='';
    $reference='';
    $doi='';
    $authors='';
    $title='';
    $pages='';
    $reference='';
    $publish_date='';
    $keywords=''; $abst=""; $doiurl="";
    $number='';
    $volume='';
    if(getRowCount($query)>=1)
    {
      while($row = fetch($query))
      {
        $article_id=$row['article_id'];
        $file_url=$row['file_url'];
        $issue_id = $row['issue_id'];
        $reference=$row['reference'];
        $doi=$row['doi'];
        $authors=strip_tags(html_entity_decode($row['authors']));
        $title=strip_tags(html_entity_decode($row['title']));
        $pages=$row['pages'];
        $keywords=strip_tags(html_entity_decode($row['keywords']));
        $abst=strip_tags(html_entity_decode($row['long_desc']));
        $doiurl=$row['doiurl'];
        $reference=$row['reference'];
        $publish_date=$row['publish_date'];
        $number=$row['number'];
        $volume=$row['volume'];
      }
    }
    $issue_no=$this->getIssueNo($issue_id);
    $year=($publish_date!="")?date("Y",strtotime($publish_date)):date("Y");
    $articleno="johs".$article_id;

    $content = "%A ".$authors."
%0 Journal Article
%T ".$title."
%J Journal of Healthcare Sciences
%N ".$issue_no."
%D ".$year."
%R ".$doi."
%V ".$volume."
%N ".$number."
%K ".$keywords."
%X ".$abst."
%U http://johs.com.sa/#/pages/issue/abstract/".$article_id."
%P ".$pages." ";
    $fp = fopen("./bib_files/$articleno.enw","w");
    fwrite($fp,$content);
    fclose($fp);
  }

  public function saverisFile()
  {
    
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    
    $sql = "SELECT * FROM articles WHERE article_id=$article_id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    
    $number='';
    $volume='';
    $issue_id='';
    $reference='';
    $doi='';
    $authors='';
    $title='';
    $pages='';
    $reference='';
    $publish_date='';
    $keywords=''; $abst=""; $doiurl="";
    $page_end='';
    $page_start='';
    
    if(getRowCount($query)>=1)
    {
      while($row = fetch($query))
      {
        $article_id=$row['article_id'];
        $file_url=$row['file_url'];
        $issue_id = $row['issue_id'];
        $reference=$row['reference'];
        $doi=$row['doi'];
        $authors=strip_tags(html_entity_decode($row['authors']));
        $title=strip_tags(html_entity_decode($row['title']));
        $pages=$row['pages'];
        $keywords=strip_tags(html_entity_decode($row['keywords']));
        $abst=strip_tags(html_entity_decode($row['long_desc']));
        $doiurl=$row['doiurl'];
        $reference=$row['reference'];
        $publish_date=$row['publish_date'];
        $number=$row['number'];
        $volume=$row['volume'];
        $page_end=$row['page_end'];
        $page_start=$row['page_start'];
      }
    }
    // echo $content;
    $issue_no=$this->getIssueNo($issue_id);
    $year=($publish_date!="")?date("Y",strtotime($publish_date)):date("Y");
    $articleno="johs".$article_id;
    $content = " RT Journal Article
A1 ".$authors."
T1 ".$title."
JF Journal of Healthcare Sciences
DO ".$doi." 
VO ".$volume." 
IS ".$issue_no." 
SP ".$page_start."
OP ".$page_end."
YR ".$year."
AB ".$abst."
UL http://johs.com.sa/#/pages/issue/abstract/".$article_id."
PN ".$pages." ";
    $fp = fopen("./bib_files/$articleno.ris","w");
    fwrite($fp,$content);
    fclose($fp);
  }

  public function savetxtFile()
  {
    $article_id   = isset ($_REQUEST['article_id']) ? $_REQUEST['article_id'] : '';
    $sql = "SELECT * FROM articles WHERE article_id=$article_id ORDER BY article_id LIMIT 1";
    $query = query($sql);
    
    $issue_id='';
    $reference='';
    $doi='';
    $authors='';
    $title='';
    $pages='';
    $reference='';
    $publish_date='';
    $keywords=''; $abst=""; $doiurl="";
    $page_end='';
    $page_start='';
    $volume='';
    if(getRowCount($query)>=1)
    {
      while($row = fetch($query))
      {
        $article_id=$row['article_id'];
        $file_url=$row['file_url'];
        $issue_id = $row['issue_id'];
        $reference=$row['reference'];
        $doi=$row['doi'];
        $authors=strip_tags(html_entity_decode($row['authors']));
        $title=strip_tags(html_entity_decode($row['title']));
        $pages=$row['pages'];
        $keywords=strip_tags(html_entity_decode($row['keywords']));
        $abst=strip_tags(html_entity_decode($row['long_desc']));
        $doiurl=$row['doiurl'];
        $reference=$row['reference'];
        $publish_date=$row['publish_date'];
        $page_end=$row['page_end'];
        $page_start=$row['page_start'];
        $volume=$row['volume'];
      }
    }
    $issue_no=$this->getIssueNo($issue_id);
    $year=($publish_date!="")?date("Y",strtotime($publish_date)):date("Y");
    $articleno="johs".$article_id;

    $content = "AU ".$authors."
T1 ".$title."
JO Journal of Healthcare Sciences
VL ".$volume."
IS ".$issue_no."
PY ".$year."
DO ".$doi."
AB ".$abst."
SP ".$page_start."
EP ".$page_end."
UR http://johs.com.sa/#/pages/issue/abstract/".$article_id."
PN ".$pages." ";
    $fp = fopen("./bib_files/$articleno.txt","w");
    fwrite($fp,$content);
    fclose($fp);
  }

   public function searchArticlesAll($org_id, $data) {

    
    $sql = "select * from articles where authors like '%".$data->search."%' or keywords like '%".$data->search."%' or title like '%".$data->search."%' and org_id = ".$org_id."";
    $dbq = query($sql);
    $data = array();
    header('Content-type: text/javascript');
    while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
    }
    print_r(json_encode(array("searchArticlesAll"=>$data)));
  }
  
  public function getEditorsChoice($org_id)
  {
    $sql = "select article_id, title, authors, fileimage from articles where editor_choice = 1 and org_id = $org_id";
    $data=array();
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
      while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
      }
    }
    print_r(json_encode(array("getEditorChoice"=>$data)));
  }

  public function getNews($org_id)
  {
    $sql = "select * from news_master where org_id = $org_id";
    $data=array();
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
      while($row = fetch_assoc($dbq)) {
      array_push($data, $row);
      }
    }
    print_r(json_encode(array("getNews"=>$data)));
  }

  public function bannerid($org_id) {
    $sql = "select name from banner where status = 'Visible' and org_id = $org_id";
    $data=array();
    $dbq = query ($sql);
    header('Content-type: text/javascript');
    if( getRowCount($dbq) >=1)
    {
      $i=0;
      while($row = fetch_assoc($dbq)) {
      $data[$i]['name'] = str_replace('\r\n', '', html_entity_decode($row['name']));
      $i++;
      }
    }
    print_r(json_encode(array("adbannerid"=>$data)));
  }

  public function register($org_id, $data) {
    $pwd = $this->encrypt($data->password);
    $sql1 = "SELECT * FROM `user_login`";
    $email_in_db = query($sql1);
    $emails=array();
    $i=0;
     
    while($row = fetch($email_in_db)){
      $emails[$i]=$row['Email'];
      $i++;
    }
    //print_r($emails);
  
    if(in_array($data->email , $emails)){
    $sql ="false";
    }
    else{
      if($data->userType == 'Author') {
      $sql = "insert into user_login(user_name, pwd, status, Type, Email, Salutation, First_Name, Qualification, Address, Mobile, org_id,user_account_type, group_id) values ('$data->email', '$pwd' , '1',  '$data->userType', '$data->email', '$data->salutation', '$data->fullname', '$data->qualification', '$data->address', '$data->mobile', '$org_id', '3', '1473')";
      }
      else{
      
      
      $sql = "insert into user_login (user_name, pwd, status, Type, Email, Salutation, First_Name, Qualification, Address, Mobile, org_id, user_account_type, group_id) values ('$data->email', '$pwd' , '1',  '$data->userType', '$data->email', '$data->salutation', '$data->fullname', '$data->qualification', '$data->address', '$data->mobile', '$org_id', '4', '1473')";
      }
    }
    $dbq = query($sql);
    print_r(json_encode(array("register"=>$dbq)));
  }

  public function getArticlesCount($org_id)
  {
    $sqlSA = "SELECT count(id) as total_countSA FROM manuscript_submit WHERE org_id = $org_id";
    $data=array();
    $dbqSA = query ($sqlSA);
    header('Content-type: text/javascript');
    $rowSA = fetch_assoc($dbqSA);
    array_push($data, $rowSA);
    $sqlPA = "SELECT count(article_id) as total_countPA FROM articles WHERE org_id = $org_id";
    $dbqPA = query ($sqlPA);
    $rowPA = fetch_assoc($dbqPA);
    array_push($data, $rowPA);
    $sqlTD = "SELECT SUM(download) as total_countTD FROM articles WHERE org_id = $org_id";
    $dbqTD = query ($sqlTD);
    $rowTD = fetch_assoc($dbqTD);
    array_push($data, $rowTD);
    print_r(json_encode(array("getArticlesCount"=>$data)));
  }
}
?>