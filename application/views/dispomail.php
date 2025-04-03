
<?php include "template/header.php"; ?>

        <div id='main-body'> 


        <div  class='centered-div2'>
          <h1>
              Your current email is <strong style='text-transform:lowercase;'><?php echo $email_account; ?></strong>
          </h1>
          <h2>You can now use it. <strong>Will be active until you close this page.</strong></h2>
          <h2>The inbox is refreshing <u><strong>automatically</strong></u> every few seconds.</h2>
        </div>
        <div  class='lefted-div2'>
         <div class="col-md-3 col-sm-3 col-xs-6 button-wrapper"> <a href="" class="btn btn-sm animated-button victoria-three">Inbox (<?php echo count($emails); ?>)</a> </div>
         <!--<div class="col-md-3 col-sm-3 col-xs-6 button-wrapper"> <a href="#" class="btn btn-sm animated-button victoria-three">Compose</a> </div>!-->

          <?php foreach($emails as $email): ?>
              <div class="modal fade modal-msg-<?php echo $email['msg_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"  id="onload" style='z-index:9999999999 !important;'>

                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title"><i class="fa fa-exclamation-circle"></i><?php echo $email['subject']; ?></h4>
                        From: <?php echo $email['from'].'<span style="font-family:monospace;">'. htmlspecialchars(' <'.$email['from_addr'].'>'); ?></span><br/>
                        Sent at: <?php echo $email['date']; ?>
                      </div>
                      <div class="modal-body">
                        <?php echo $email['body']; ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Got it.</button>
                      </div>
                    </div>

                  </div>
              </div>
          <?php endforeach; ?>

        <div class='clear'></div>


          <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
              <thead class="tbl-header">
                <tr>
                  <th style='width:3%;'>#</th>
                  <th>Subject</th>
                  <th>From</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach($emails as $email): ?>
                  <tr data-toggle="modal"  data-target=".modal-msg-<?php echo $email['msg_id']; ?>">
                    <td><?php echo $email['msg_id']; ?></td>
                    <td class='subject'><?php echo $email['subject']; ?></td>
                    <td class='email'><?php echo $email['from'].'<span style="font-family:monospace;">'. htmlspecialchars(' <'.$email['from_addr'].'>'); ?></span></td>
                    <td><?php echo $email['date']; ?></td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        </div>
        </div>


<script>var dispomail = 1;</script>

<?php include "template/footer.php"; ?>