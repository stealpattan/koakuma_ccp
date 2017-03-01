<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>企業情報の登録</title>
  </head>
  <body>
    <form action="recruitment-input_do.php" method="post">
      <dl>
        <dt><label for="company_name">企業名</label></dt>
        <dd><input type="text" name="company_name" maxlength="50"></dd>
        <dt><label for="indust_type">業種</label></dt>
        <dd><input type="text" name="indust_type" maxlength="15"></dd>
        <dt><label for="address">住所</label></dt>
        <dd><input type="text" name="address" maxlength="50"></dd>
        <dt><label for="url_list">HP_url</label></dt>
        <dd><input type="text" name="url_list" maxlength="50"></dd>
      </dl>
      <input type="submit" value="登録">
    </form>
  </body>
</html>
