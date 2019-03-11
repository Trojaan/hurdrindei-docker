                <div id="login">
                    <?php
                        if($this->session->userdata('logged_in')) {
                          redirect(site_url('paginaoverzicht'));
                        }
                        
                        else  {
                    ?>
                            <div id="loginform">
                              <div class="logoLogin"></div>
                              <div id="logged"></div>
                              <div id="loginerror"></div>
                                <?=form_open('user/login',array('id' => 'userlogin'));?>
                                <table>
                                  <tr>
                                    <td>
                                      <label for="username">Gebruikersnaam:</label>
                                    </td>
                                    <td>
                                      <input class="logininput" type="text" name="username" id="username"  />
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                       <label for="password">Wachtwoord:</label>
                                    </td>
                                    <td>
                                       <input class="logininput" type="password" name="password" id="password" />
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <input class="loginsubmit" type="submit" value="Login" />
                                    </td>
                                  </tr>
                                </table>
                                <?=form_close();?>
                            </div>
                    <?php
                        }
                    ?>
                </div>
