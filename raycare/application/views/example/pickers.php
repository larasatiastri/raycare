<!-- BEGIN PAGE CONTENT INNER -->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light form-fit">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Date Pickers</span>
								<span class="caption-helper">alert samples...</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form action="#" class="form-horizontal form-bordered">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-3">Default Datepicker</label>
										<div class="col-md-3">
											<input class="form-control form-control-inline input-medium date-picker" size="16" type="text" value=""/>
											<span class="help-block">
											Select date </span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Disable Past Dates</label>
										<div class="col-md-3">
											<div class="input-group input-medium date date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
												<input type="text" class="form-control" readonly>
												<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<!-- /input-group -->
											<span class="help-block">
											Select date </span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Start With Years</label>
										<div class="col-md-3">
											<div class="input-group input-medium date date-picker" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
												<input type="text" class="form-control" readonly>
												<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<!-- /input-group -->
											<span class="help-block">
											Select date </span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Months Only</label>
										<div class="col-md-3">
											<div class="input-group input-medium date date-picker" data-date="10/2012" data-date-format="mm/yyyy" data-date-viewmode="years" data-date-minviewmode="months">
												<input type="text" class="form-control" readonly>
												<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<!-- /input-group -->
											<span class="help-block">
											Select month only </span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Date Range</label>
										<div class="col-md-4">
											<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
												<input type="text" class="form-control" name="from">
												<span class="input-group-addon">
												to </span>
												<input type="text" class="form-control" name="to">
											</div>
											<!-- /input-group -->
											<span class="help-block">
											Select date range </span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Inline</label>
										<div class="col-md-3">
											<div class="date-picker" data-date-format="mm/dd/yyyy">
											</div>
										</div>
									</div>
									<div class="form-group last">
										<label class="control-label col-md-3"></label>
										<div class="col-md-3">
											<a class="btn yellow" href="#form_modal2" data-toggle="modal">
											View Datepicker in modal <i class="fa fa-share"></i>
											</a>
										</div>
									</div>
								</div>
							</form>
							<div id="form_modal2" class="modal fade" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Datepickers In Modal</h4>
										</div>
										<div class="modal-body">
											<form action="#" class="form-horizontal">
												<div class="form-group">
													<label class="control-label col-md-4">Default Datepicker</label>
													<div class="col-md-8">
														<input class="form-control input-medium date-picker" size="16" type="text" value=""/>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Disable Past Dates</label>
													<div class="col-md-8">
														<div class="input-group input-medium date date-picker" data-date-format="dd-mm-yyyy" data-date-start-date="+0d">
															<input type="text" class="form-control" readonly>
															<span class="input-group-btn">
															<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
														<!-- /input-group -->
														<span class="help-block">
														Select date </span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Start With Years</label>
													<div class="col-md-8">
														<div class="input-group input-medium date date-picker" data-date="12-02-2012" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
															<input type="text" class="form-control" readonly>
															<span class="input-group-btn">
															<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
														<!-- /input-group -->
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Months Only</label>
													<div class="col-md-8">
														<div class="input-group input-medium date date-picker" data-date="10/2012" data-date-format="mm/yyyy" data-date-viewmode="years" data-date-minviewmode="months">
															<input type="text" class="form-control" readonly>
															<span class="input-group-btn">
															<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
														<!-- /input-group -->
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Date Range</label>
													<div class="col-md-8">
														<div class="input-group input-medium date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
															<input type="text" class="form-control" name="from">
															<span class="input-group-addon">
															to </span>
															<input type="text" class="form-control" name="to">
														</div>
														<!-- /input-group -->
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
											<button class="btn green" data-dismiss="modal">Save changes</button>
										</div>
									</div>
								</div>
							</div>
							<!-- END FORM-->
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light form-fit">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-calendar font-red-sunglo"></i>
								<span class="caption-subject font-red-sunglo bold uppercase">Datetime Pickers</span>
								<span class="caption-helper">alert samples...</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form action="#" class="form-horizontal form-bordered">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-3">Default Datetimepicker</label>
										<div class="col-md-4">
											<div class="input-group date form_datetime">
												<input type="text" size="16" readonly class="form-control">
												<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<!-- /input-group -->
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Advance Datetimepicker</label>
										<div class="col-md-4">
											<div class="input-group date form_datetime" data-date="2012-12-21T15:25:00Z">
												<input type="text" size="16" readonly class="form-control">
												<span class="input-group-btn">
												<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
												</span>
												<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<!-- /input-group -->
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Meridian Format</label>
										<div class="col-md-4">
											<div class="input-group date form_meridian_datetime" data-date="2012-12-21T15:25:00Z">
												<input type="text" size="16" readonly class="form-control">
												<span class="input-group-btn">
												<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
												</span>
												<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<!-- /input-group -->
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Inline</label>
										<div class="col-md-4">
											<div class="form_datetime" data-date="2012-12-21T15:25:00Z">
											</div>
											<!-- /input-group -->
										</div>
									</div>
									<div class="form-group last">
										<label class="control-label col-md-3"></label>
										<div class="col-md-4">
											<a class="btn yellow" href="#form_modal1" data-toggle="modal">
											View Datetimepicker in modal <i class="fa fa-share"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn purple"><i class="fa fa-check"></i> Submit</button>
											<button type="button" class="btn default">Cancel</button>
										</div>
									</div>
								</div>
							</form>
							<div id="form_modal1" class="modal fade" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Datetimepicker in Modal</h4>
										</div>
										<div class="modal-body">
											<form action="#" class="form-horizontal">
												<div class="form-group">
													<label class="control-label col-md-4">Default Datetimepicker</label>
													<div class="col-md-8">
														<div class="input-group date form_datetime input-medium">
															<input type="text" size="16" readonly class="form-control">
															<span class="input-group-btn">
															<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
														<!-- /input-group -->
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Advance Datetimepicker</label>
													<div class="col-md-8">
														<div class="input-group date form_datetime input-large" data-date="2012-12-21T15:25:00Z">
															<input type="text" size="16" readonly class="form-control">
															<span class="input-group-btn">
															<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
															</span>
															<span class="input-group-btn">
															<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
														<!-- /input-group -->
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Meridian Format</label>
													<div class="col-md-8">
														<div class="input-group date form_meridian_datetime input-large" data-date="2012-12-21T15:25:00Z">
															<input type="text" size="16" readonly class="form-control">
															<span class="input-group-btn">
															<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
															</span>
															<span class="input-group-btn">
															<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
														<!-- /input-group -->
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
											<button class="btn green btn-primary" data-dismiss="modal">Save changes</button>
										</div>
									</div>
								</div>
							</div>
							<!-- END FORM-->
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Time Pickers</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form action="#" class="form-horizontal form-bordered">
								<div class="form-body form">
									<div class="form-group">
										<label class="control-label col-md-3">Default Timepicker</label>
										<div class="col-md-3">
											<div class="input-icon">
												<i class="fa fa-clock-o"></i>
												<input type="text" class="form-control timepicker timepicker-default">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Without Seconds</label>
										<div class="col-md-3">
											<div class="input-group">
												<input type="text" class="form-control timepicker timepicker-no-seconds">
												<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">24hr Timepicker</label>
										<div class="col-md-3">
											<div class="input-group">
												<input type="text" class="form-control timepicker timepicker-24">
												<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3"></label>
										<div class="col-md-3">
											<a class="btn yellow" href="#form_modal4" data-toggle="modal">
											View Timepicker in modal <i class="fa fa-share"></i>
											</a>
										</div>
									</div>
								</div>
							</form>
							<div id="form_modal4" class="modal fade" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Timepickers In Modal</h4>
										</div>
										<div class="modal-body">
											<form action="#" class="form-horizontal">
												<div class="form-group">
													<label class="control-label col-md-4">Default Timepicker</label>
													<div class="col-md-5">
														<div class="input-icon">
															<i class="fa fa-clock-o"></i>
															<input type="text" class="form-control timepicker timepicker-default">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">Without Seconds</label>
													<div class="col-md-5">
														<div class="input-group">
															<input type="text" class="form-control timepicker timepicker-no-seconds">
															<span class="input-group-btn">
															<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
															</span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-4">24hr Timepicker</label>
													<div class="col-md-5">
														<div class="input-group">
															<input type="text" class="form-control timepicker timepicker-24">
															<span class="input-group-btn">
															<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
															</span>
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
											<button class="btn green btn-primary" data-dismiss="modal">Save changes</button>
										</div>
									</div>
								</div>
							</div>
							<!-- END FORM-->
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Clockface Time Pickers</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body form">
							<form action="#" class="form-horizontal form-bordered">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-3">Input</label>
										<div class="col-md-3">
											<input type="text" value="2:30 PM" data-format="hh:mm A" class="form-control clockface_1"/>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Button</label>
										<div class="col-md-3">
											<div class="input-group">
												<input type="text" id="clockface_2" value="14:30" class="form-control" readonly=""/>
												<span class="input-group-btn">
												<button class="btn default" type="button" id="clockface_2_toggle"><i class="fa fa-clock-o"></i></button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Inline</label>
										<div class="col-md-4">
											<div class="well clockface_3" style="padding: 0; width: 162px;">
											</div>
										</div>
									</div>
									<div class="form-group last">
										<label class="control-label col-md-3"></label>
										<div class="col-md-3">
											<a class="btn yellow" href="#form_modal5" data-toggle="modal">
											View Clockface Time Pickers in modal <i class="fa fa-share"></i>
											</a>
										</div>
									</div>
								</div>
							</form>
							<div id="form_modal5" class="modal fade" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Clockface In Modal</h4>
										</div>
										<div class="modal-body">
											<form action="#" class="form-horizontal">
												<div class="form-group">
													<label class="control-label col-md-3">Input</label>
													<div class="col-md-4">
														<input type="text" value="2:30 PM" data-format="hh:mm A" class="form-control clockface_1"/>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Button</label>
													<div class="col-md-4">
														<div class="input-group">
															<input type="text" id="clockface_2_modal" value="14:30" class="form-control" readonly=""/>
															<span class="input-group-btn">
															<button class="btn default" type="button" id="clockface_2_modal_toggle"><i class="fa fa-clock-o"></i></button>
															</span>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Inline</label>
													<div class="col-md-4">
														<div class="well clockface_3" style="padding: 0; width: 162px;">
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button class="btn default" data-dismiss="modal" aria-hidden="true">Close</button>
											<button class="btn green" data-dismiss="modal">Save changes</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Daterangepickers</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form action="#" class="form-horizontal form-bordered">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-3">Default Date Ranges</label>
										<div class="col-md-4">
											<div class="input-group" id="defaultrange">
												<input type="text" class="form-control">
												<span class="input-group-btn">
												<button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
										</div>
									</div>
									<div class="form-group ">
										<label class="control-label col-md-3">Advance Date Ranges</label>
										<div class="col-md-4">
											<div id="reportrange" class="btn default">
												<i class="fa fa-calendar"></i>
												&nbsp; <span>
												</span>
												<b class="fa fa-angle-down"></b>
											</div>
										</div>
									</div>
									<div class="form-group last">
										<label class="control-label col-md-3"></label>
										<div class="col-md-4">
											<a class="btn yellow" href="#daterangepicker_modal" data-toggle="modal">
											View Daterange Picker in modal <i class="fa fa-share"></i>
											</a>
										</div>
									</div>
								</div>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-offset-3 col-md-9">
											<button type="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
											<button type="button" class="btn default">Cancel</button>
										</div>
									</div>
								</div>
							</form>
							<div id="daterangepicker_modal" class="modal fade" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Daterange Picker in Modal</h4>
										</div>
										<div class="modal-body">
											<form action="#" class="form-horizontal">
												<div class="form-group">
													<label class="control-label col-md-4">Default Date Ranges</label>
													<div class="col-md-8">
														<div class="input-group input-large" id="defaultrange_modal">
															<input type="text" class="form-control">
															<span class="input-group-btn">
															<button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
											<button class="btn green btn-primary" data-dismiss="modal">Save changes</button>
										</div>
									</div>
								</div>
							</div>
							<!-- END FORM-->
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-cogs font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Color Pickers</span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<form action="#" class="form-horizontal form-bordered">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-3">Default</label>
										<div class="col-md-3">
											<input type="text" class="colorpicker-default form-control" value="#8fff00"/>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">RGBA</label>
										<div class="col-md-3">
											<input type="text" class="colorpicker-rgba form-control" value="rgb(0,194,255,0.78)" data-color-format="rgba"/>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">As Component</label>
										<div class="col-md-3">
											<div class="input-group color colorpicker-default" data-color="#3865a8" data-color-format="rgba">
												<input type="text" class="form-control" value="#3865a8" readonly>
												<span class="input-group-btn">
												<button class="btn default" type="button"><i style="background-color: #3865a8;"></i>&nbsp;</button>
												</span>
											</div>
											<!-- /input-group -->
										</div>
									</div>
									<div class="form-group last">
										<label class="control-label col-md-3"></label>
										<div class="col-md-4">
											<a class="btn yellow" href="#form_modal3" data-toggle="modal">
											View colorpicker in modal <i class="fa fa-share"></i>
											</a>
										</div>
									</div>
								</div>
							</form>
							<div id="form_modal3" class="modal fade" role="dialog" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Colorpicker in Modal</h4>
										</div>
										<div class="modal-body">
											<form action="#" class="form-horizontal">
												<div class="form-group">
													<label class="control-label col-md-3">Default</label>
													<div class="col-md-5">
														<input type="text" class="colorpicker-default form-control" value="#8fff00"/>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">RGBA</label>
													<div class="col-md-5">
														<input type="text" class="colorpicker-rgba form-control" value="rgb(0,194,255,0.78)" data-color-format="rgba"/>
													</div>
												</div>
												<div class="form-group last">
													<label class="control-label col-md-3">As Component</label>
													<div class="col-md-5">
														<div class="input-group color colorpicker-default" data-color="#3865a8" data-color-format="rgba">
															<input type="text" class="form-control" value="#3865a8" readonly>
															<span class="input-group-btn">
															<button class="btn default" type="button"><i style="background-color: #3865a8;"></i>&nbsp;</button>
															</span>
														</div>
														<!-- /input-group -->
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
											<button class="btn green btn-primary" data-dismiss="modal">Save changes</button>
										</div>
									</div>
								</div>
							</div>
							<!-- END FORM-->
						</div>
					</div>
					<!-- END PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT INNER -->