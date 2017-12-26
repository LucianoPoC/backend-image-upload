import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class App extends Component {
    render() {
        return (
            <div className="container">
                <div className="row">
                    <div className="col-md-8 col-md-offset-2">
                        <div className="panel panel-default">
                            <div className="panel-heading">Image Gallery</div>
                            <form action="">
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <label>Title:</label>
                                            <input type="text" className="form-control"/>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <label>File:</label>
                                            <input type="file" className="form-control col-md-6"/>
                                        </div>
                                    </div>
                                </div><br />
                                <div className="form-group">
                                    <button className="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<Example />, document.getElementById('app'));
}
